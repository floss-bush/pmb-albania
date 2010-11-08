/*
 * Copyright (c) 1995-2003, Index Data.
 * See the file LICENSE for details.
 *
 * $Id: ztest.c,v 1.1 2004-06-09 08:19:42 touraine37 Exp $
 */

/*
 * Demonstration of simple server
 */
#if (defined(_WIN32) || defined(_WIN64)) && !defined(__WIN__)
#define __WIN__
#endif

#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

#if defined(__WIN__)

#ifndef _WINSOCKAPI_
#include <winsock.h>
#endif
#include <windows.h>
#include <initguid.h>
#include <errno.h>
#include <signal.h>
#include <tchar.h>

#else

#include <errno.h>
#include <signal.h>
#include <netdb.h>
#include <unistd.h>
#include <string.h>
#include <arpa/inet.h>
#include <netinet/in.h>
#include <sys/socket.h>

#endif

#include <yaz/yaz-util.h>
#include <yaz/backend.h>
#include <yaz/ill.h>

Z_GenericRecord *dummy_grs_record (int num, ODR o);
char *dummy_marc_record (int num, ODR odr);
char *dummy_xml_record (int num, ODR odr);

int ztest_search (void *handle, bend_search_rr *rr);
int ztest_sort (void *handle, bend_sort_rr *rr);
int ztest_present (void *handle, bend_present_rr *rr);
int ztest_esrequest (void *handle, bend_esrequest_rr *rr);
int ztest_delete (void *handle, bend_delete_rr *rr);

/* définitions pour fonction HTTP GET */
#define PACKET_SIZE  1024

int to_server_socket = -1;
char server_name[100]; // nom du host du serveur
int port;
char request_path[PACKET_SIZE];
char database[100];
char bulk[4096];
char id_set[4096];
int http_err;
char http_err_string[255];
char http_content[4096];

char url_encode_t[256][4] = {
	"%00","%01","%02","%03","%04","%05","%06","%07","%08","%09","%0A","%0B","%0C","%0D","%0E","%0F",
	"%10","%11","%12","%13","%14","%15","%16","%17","%18","%19","%1A","%1B","%1C","%1D","%1E","%1F",
	"+","%21","%22","%23","%24","%25","%26","%27","%28","%29","%2A","%2B","%2C","%2D","%2E","%2F",
	"0","1","2","3","4","5","6","7","8","9","%3A","%3B","%3C","%3D","%3E","%3F",
	"%40","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O",
	"P","Q","R","S","T","U","V","W","X","Y","Z","%5B","%5C","%5D","%5E","%5F",
	"%60","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o",
	"p","q","r","s","t","u","v","w","x","y","z","%7B","%7C","%7D","%7E","%7F",
	"%80","%81","%82","%83","%84","%85","%86","%87","%88","%89","%8A","%8B","%8C","%8D","%8E","%8F",
	"%90","%91","%92","%93","%94","%95","%96","%97","%98","%99","%9A","%9B","%9C","%9D","%9E","%9F",
	"%A0","%A1","%A2","%A3","%A4","%A5","%A6","%A7","%A8","%A9","%AA","%AB","%AC","%AD","%AE","%AF",	
	"%B0","%B1","%B2","%B3","%B4","%B5","%B6","%B7","%B8","%B9","%BA","%BB","%BC","%BD","%BE","%BF",
	"%C0","%C1","%C2","%C3","%C4","%C5","%C6","%C7","%C8","%C9","%CA","%CB","%CC","%CD","%CE","%CF",
	"%D0","%D1","%D2","%D3","%D4","%D5","%D6","%D7","%D8","%D9","%DA","%DB","%DC","%DD","%DE","%DF",
	"%E0","%E1","%E2","%E3","%E4","%E5","%E6","%E7","%E8","%E9","%EA","%EB","%EC","%ED","%EE","%EF",
	"%F0","%F1","%F2","%F3","%F4","%F5","%F6","%F7","%F8","%F9","%FA","%FB","%FC","%FD","%FE","%FF"};

int n_results;
int cur_results;
char *ids[100];

/* Fonction d'encodage URL d'une chaine */	
char * url_encode(char * in) {
	int i;
	
	char *out;
	
	out = (char *)malloc(strlen(in)*3+1);
	
	strcpy(out,"");
	for (i=0; i<strlen(in); i++) {
		strcat(out,url_encode_t[in[i]]);
	}
	return out;
}

/* Fonction de récupération du contenu renvoyé par le serveur */
int get_http_content() {
	char *pos;
	char *pos1;
	
	//Recherche du 1er @ (numéro d'erreur)
	pos = strstr(bulk,"@");
	if (pos==NULL) return 1;
	*pos = 0x00;
	
	http_err = atoi(bulk);
	
	//Recherche de 2ème @ (chaine d'erreur)
	pos++;
	pos1 = strstr(pos,"@");
	if (pos1==NULL) return 1;
	*pos1 = 0x00;
	strcpy(http_err_string,pos);
	
	//Récupération du contenu
	pos1++;
	strcpy(http_content,pos1);
	return 0;
}

#if defined(__WIN__)

void bcopy( void * source, void * destination, int size )
{
   int i;
   char * src = ( char * ) source;
   char * dst = ( char * ) destination;

   for( i=0; i<size; i++ )
      dst[i] = src[i];
}

void bzero( void * destination, int size )
{
   unsigned int i;	
   char * dst = ( char * ) destination;

   for( i=0; i<size; i++ )
      dst[i] = 0x00;
}

int readn(int fd, char *ptr, int n){
int nl, nr;

        nl = n;
        while ( nl > 0 ) {
                nr = recv(fd,ptr,nl,0);
                if (nr < 0 )
                        return nr;     /*error*/
                else
                        if ( nr == 0 )
                                break;
                nl -= nr;
                ptr += nr;
        }
        *ptr=0x00;
        return (n-nl);
}

int appli (char *query) {
	char buffer[PACKET_SIZE+1];
	char line[PACKET_SIZE+2];
	int rc;
	int i = 0;

 	sprintf(buffer,"");
	sprintf(bulk,"");
  	
  	sprintf(line,"GET %s?%s\r\n",request_path,query);

  	yaz_log(LOG_LOG,"http_get : %s",line);
 
        send(to_server_socket,line,strlen(line)+1,0);

        do {
                rc = readn(to_server_socket,buffer,PACKET_SIZE);
    			if (rc<0) return 1;
    			strcat(bulk,buffer);
        		} while ( rc != 0 ) ;
        return 0;
}

int http_get(char *query)
{

struct sockaddr_in serverSockAddr;    /* addresse de la socket */
struct hostent *serverHostEnt;        /* description du host serveur */
long hostAddr;                       /* addr du serveur */

     /* initialise a zero serverSockAddr */
  bzero(&serverSockAddr,sizeof(serverSockAddr));
     /* converti l'adresse ip  en entier long */
  hostAddr = inet_addr(server_name);
  if ( (long)hostAddr != (long)-1)
      bcopy(&hostAddr,&serverSockAddr.sin_addr,sizeof(hostAddr));
  else     /* si on a donne un nom  */
  {
      serverHostEnt = gethostbyname(server_name);
      if (serverHostEnt == NULL)
      {
         return 1;
      }
      bcopy(serverHostEnt->h_addr,
            &serverSockAddr.sin_addr,serverHostEnt->h_length);
  }
  serverSockAddr.sin_port = htons(port);         /* host to network port  */
  serverSockAddr.sin_family = AF_INET;            /* AF_*** : INET=internet */

  /* creation de la socket */
  if ( (to_server_socket = socket(AF_INET,SOCK_STREAM,0)) < 0)
  {
      return 1;
  }
  /* requete de connexion */
  if(connect(to_server_socket,(struct sockaddr *)&serverSockAddr,
             sizeof(serverSockAddr))<0)
  {
      return 1;
  }

  if (appli(query)) return 1;

  /* fermeture de la connection */
  shutdown(to_server_socket,2);
  closesocket(to_server_socket);
  
  return get_http_content();
}

#else

int readn(int fd, char *ptr, int n) {
  int nl, nr;
  
  nl = n;
  while ( nl > 0 ) {
    nr = read(fd,ptr,nl);
    if (nr < 0 )
      return nr;     /*error*/
    else
      if ( nr == 0 )
	break;
    nl -= nr;
    ptr += nr;
  }
  *ptr = 0x00;
  return (n-nl);
}

int requete (char *query) {
  char buffer[PACKET_SIZE+1];
  char line[PACKET_SIZE+2];
  int rc;
   
  sprintf(bulk,"");
  sprintf(buffer,"");
  
  sprintf(line,"GET %s?%s\r\n",request_path,query);

  yaz_log(LOG_LOG,"http_get : %s",line);

  send(to_server_socket,line,strlen(line)+1,0);
  do {
    rc = readn(to_server_socket,buffer,PACKET_SIZE);
    if (rc<0) return 1;
    strcat(bulk,buffer);
  } while ( rc != 0 ) ;
  return 0;
}

int http_get(char *query)
{
  struct sockaddr_in serverSockAddr;
  struct hostent *serverHostEnt;
  unsigned long hostAddr;
   
  /* initialise a zero serverSockAddr */
  bzero((void *)&serverSockAddr,sizeof(serverSockAddr));
  /* converti l'adresse ip en entier long */
  hostAddr = inet_addr(server_name);
  if ( (long)hostAddr != (long)-1)
    bcopy((void *)&hostAddr,(void *)&serverSockAddr.sin_addr,sizeof(hostAddr));
  else                /* si on a donné un nom  */
    {
      serverHostEnt = gethostbyname(server_name);
      if (serverHostEnt == NULL) {
	return 1;
      }
      bcopy((void *)serverHostEnt->h_addr,(void *)&serverSockAddr.sin_addr,serverHostEnt->h_length);
    }
  serverSockAddr.sin_port = htons(port);   /* host to network port  */
  serverSockAddr.sin_family = AF_INET;     /* AF_*** : INET=internet */
  
  /* creation de la socket */
  if ( (to_server_socket = socket(AF_INET,SOCK_STREAM,0)) < 0)
    {
      return 1;
    }
  /* requete de connexion */
  if(connect(to_server_socket,(struct sockaddr *)&serverSockAddr,sizeof(serverSockAddr))<0)
    {
      return 1;
    }
  
  if (requete(query)) return 1;
  
  /* fermeture de la connection */
  shutdown(to_server_socket,2);
  close(to_server_socket);
  return get_http_content();
}

#endif

int get_query(char *result, Z_RPNStructure *query_s, char *err_string, int level) {
  /*Analyse de la requete*/
  char result1[500];
  char result2[500];
  char ope[8];
  char escaped_buf[2048];
  int err;
  int use;

  err=0;
  sprintf(err_string,"");

  if (query_s->which==Z_RPNStructure_complex) {
    
    switch (query_s->u.complex->roperator->which) {
    case Z_Operator_and:
      sprintf(ope,"and");
      break;
    case Z_Operator_or:
      sprintf(ope,"or");
      break;
    case  Z_Operator_and_not:
      sprintf(ope,"and not");
      break;
    default:
      sprintf(ope,"");
      err=110;
      return err;
      break;
    }
    err=get_query(result1,query_s->u.complex->s1,err_string, level+1);
    if (err) return err;
    err=get_query(result2,query_s->u.complex->s2,err_string, level+1);
    if (err) return err;

    sprintf(result,"%s arg%i!1(%s) arg%i!2(%s)",ope,level,result1,level,result2);
  } else {
    //Nombre d'attributs pour le terme > 1 ?
    if (query_s->u.simple->u.attributesPlusTerm->attributes->num_attributes!=1) {
      err=123;
      return err;
    }
    //Type d'attribut <> 1 ?
    if (*query_s->u.simple->u.attributesPlusTerm->attributes->attributes[0]->attributeType!=1) {
      err=113;
      return err;
    }
    //Attribut autorisé ?
    use = *query_s->u.simple->u.attributesPlusTerm->attributes->attributes[0]->value.numeric;
    /*
    if ((use!=7)&&(use!=1003)&&(use!=4)) {
      err=114;
      sprintf(err_string,"1=%i",use);
      return err;
    }*/
    //Type de Terme autorisé ?
    if (query_s->u.simple->u.attributesPlusTerm->term->which!=1) {
      err=229;
      return err;
    } 
    strcpy(escaped_buf,query_s->u.simple->u.attributesPlusTerm->term->u.general->buf);
   	sprintf(result,"%i=%s",use,escaped_buf);
 }
  return 0;
}

int ztest_search (void *handle, bend_search_rr *rr)
{
  int err;
  char query[1024];
  char query_final[2048];
  char err_string[255];
  int i;
  
  if (strcmp(rr->setname,"1")) {
    rr->errcode = 2;
    return 0;
  }
    
   //Si la requete n'est pas de type_1
  if (rr->query->which!=2) {
    rr->errcode = 107;
    return 0;
  }

  err=get_query(query,rr->query->u.type_1->RPNStructure,err_string,0);
  if (!err) {  
    yaz_log(LOG_LOG,"Translated Query = %s",query);
  } else {
      rr->errcode = err;
      rr->errstring = err_string;
      return 0;
    }

    if (rr->num_bases != 1)
    {
        rr->errcode = 23;
        return 0;
    }
    if (yaz_matchstr (rr->basenames[0], database))
    {
        rr->errcode = 109;
        rr->errstring = rr->basenames[0];
        return 0;
    }
  
  	/* Lancement de la recherche */
  	sprintf(query_final,"query=%s&command=search",url_encode(query));
  	yaz_log(LOG_LOG,"query:%s",query_final);
  	
  	if (http_get(query_final)) {
  		rr->errcode=2;
  		return 0;	
  	}
  	if (http_err==3) {
  			rr->errcode=114;
  			rr->errstring=http_err_string;
  			return 0;
  	}
  	strcpy(id_set,http_content);
  	
  	n_results=atoi(strtok(id_set,"@"));
  	cur_results=0;
  	i=0;
  	while ((ids[i]=strtok(NULL,"@"))!=NULL) {
  		i++;
  	}
 	
 	rr->hits = n_results;
    return 0;
}


/* this huge function handles extended services */
int ztest_esrequest (void *handle, bend_esrequest_rr *rr)
{
    /* user-defined handle - created in bend_init */
    int *counter = (int*) handle;  

    yaz_log(LOG_LOG, "ESRequest no %d", *counter);

    (*counter)++;

    if (rr->esr->packageName)
    	yaz_log(LOG_LOG, "packagename: %s", rr->esr->packageName);
    yaz_log(LOG_LOG, "Waitaction: %d", *rr->esr->waitAction);


    yaz_log(LOG_LOG, "function: %d", *rr->esr->function);

    if (!rr->esr->taskSpecificParameters)
    {
        yaz_log (LOG_WARN, "No task specific parameters");
    }
    else if (rr->esr->taskSpecificParameters->which == Z_External_itemOrder)
    {
    	Z_ItemOrder *it = rr->esr->taskSpecificParameters->u.itemOrder;
	yaz_log (LOG_LOG, "Received ItemOrder");
        if (it->which == Z_IOItemOrder_esRequest)
	{
	    Z_IORequest *ir = it->u.esRequest;
	    Z_IOOriginPartToKeep *k = ir->toKeep;
	    Z_IOOriginPartNotToKeep *n = ir->notToKeep;
	    
	    if (k && k->contact)
	    {
	        if (k->contact->name)
		    yaz_log(LOG_LOG, "contact name %s", k->contact->name);
		if (k->contact->phone)
		    yaz_log(LOG_LOG, "contact phone %s", k->contact->phone);
		if (k->contact->email)
		    yaz_log(LOG_LOG, "contact email %s", k->contact->email);
	    }
	    if (k->addlBilling)
	    {
	        yaz_log(LOG_LOG, "Billing info (not shown)");
	    }
	    
	    if (n->resultSetItem)
	    {
	        yaz_log(LOG_LOG, "resultsetItem");
		yaz_log(LOG_LOG, "setId: %s", n->resultSetItem->resultSetId);
		yaz_log(LOG_LOG, "item: %d", *n->resultSetItem->item);
	    }
	    if (n->itemRequest)
	    {
		Z_External *r = (Z_External*) n->itemRequest;
		ILL_ItemRequest *item_req = 0;
		ILL_APDU *ill_apdu = 0;
		if (r->direct_reference)
		{
		    oident *ent = oid_getentbyoid(r->direct_reference);
		    if (ent)
			yaz_log(LOG_LOG, "OID %s", ent->desc);
                    if (ent && ent->value == VAL_TEXT_XML)
                    {
			yaz_log (LOG_LOG, "ILL XML request");
                        if (r->which == Z_External_octet)
                            yaz_log (LOG_LOG, "%.*s", r->u.octet_aligned->len,
                                     r->u.octet_aligned->buf); 
                    }
		    if (ent && ent->value == VAL_ISO_ILL_1)
		    {
			yaz_log (LOG_LOG, "Decode ItemRequest begin");
			if (r->which == ODR_EXTERNAL_single)
			{
			    odr_setbuf(rr->decode,
				       (char *) r->u.single_ASN1_type->buf,
				       r->u.single_ASN1_type->len, 0);
			    
			    if (!ill_ItemRequest (rr->decode, &item_req, 0, 0))
			    {
				yaz_log (LOG_LOG,
                                    "Couldn't decode ItemRequest %s near %d",
                                       odr_errmsg(odr_geterror(rr->decode)),
                                       odr_offset(rr->decode));
                            }
			    else
			        yaz_log(LOG_LOG, "Decode ItemRequest OK");
			    if (rr->print)
			    {
				ill_ItemRequest (rr->print, &item_req, 0,
                                    "ItemRequest");
				odr_reset (rr->print);
 			    }
			}
			if (!item_req && r->which == ODR_EXTERNAL_single)
			{
			    yaz_log (LOG_LOG, "Decode ILL APDU begin");
			    odr_setbuf(rr->decode,
				       (char*) r->u.single_ASN1_type->buf,
				       r->u.single_ASN1_type->len, 0);
			    
			    if (!ill_APDU (rr->decode, &ill_apdu, 0, 0))
			    {
				yaz_log (LOG_LOG,
                                    "Couldn't decode ILL APDU %s near %d",
                                       odr_errmsg(odr_geterror(rr->decode)),
                                       odr_offset(rr->decode));
                                yaz_log(LOG_LOG, "PDU dump:");
                                odr_dumpBER(yaz_log_file(),
                                     (char *) r->u.single_ASN1_type->buf,
                                     r->u.single_ASN1_type->len);
                            }
			    else
			        yaz_log(LOG_LOG, "Decode ILL APDU OK");
			    if (rr->print)
                            {
				ill_APDU (rr->print, &ill_apdu, 0,
                                    "ILL APDU");
				odr_reset (rr->print);
			    }
			}
		    }
		}
		if (item_req)
		{
		    yaz_log (LOG_LOG, "ILL protocol version = %d",
			     *item_req->protocol_version_num);
		}
	    }
            if (k)
            {

		Z_External *ext = (Z_External *)
                    odr_malloc (rr->stream, sizeof(*ext));
		Z_IUOriginPartToKeep *keep = (Z_IUOriginPartToKeep *)
                    odr_malloc (rr->stream, sizeof(*keep));
		Z_IOTargetPart *targetPart = (Z_IOTargetPart *)
		    odr_malloc (rr->stream, sizeof(*targetPart));

		rr->taskPackage = (Z_TaskPackage *)
                    odr_malloc (rr->stream, sizeof(*rr->taskPackage));
		rr->taskPackage->packageType =
		    odr_oiddup (rr->stream, rr->esr->packageType);
		rr->taskPackage->packageName = 0;
		rr->taskPackage->userId = 0;
		rr->taskPackage->retentionTime = 0;
		rr->taskPackage->permissions = 0;
		rr->taskPackage->description = 0;
		rr->taskPackage->targetReference = (Odr_oct *)
		    odr_malloc (rr->stream, sizeof(Odr_oct));
		rr->taskPackage->targetReference->buf =
		    (unsigned char *) odr_strdup (rr->stream, "911");
		rr->taskPackage->targetReference->len =
		    rr->taskPackage->targetReference->size =
		    strlen((char *) (rr->taskPackage->targetReference->buf));
		rr->taskPackage->creationDateTime = 0;
		rr->taskPackage->taskStatus = odr_intdup(rr->stream, 0);
		rr->taskPackage->packageDiagnostics = 0;
		rr->taskPackage->taskSpecificParameters = ext;

		ext->direct_reference =
		    odr_oiddup (rr->stream, rr->esr->packageType);
		ext->indirect_reference = 0;
		ext->descriptor = 0;
		ext->which = Z_External_itemOrder;
		ext->u.itemOrder = (Z_ItemOrder *)
		    odr_malloc (rr->stream, sizeof(*ext->u.update));
		ext->u.itemOrder->which = Z_IOItemOrder_taskPackage;
		ext->u.itemOrder->u.taskPackage =  (Z_IOTaskPackage *)
		    odr_malloc (rr->stream, sizeof(Z_IOTaskPackage));
		ext->u.itemOrder->u.taskPackage->originPart = k;
		ext->u.itemOrder->u.taskPackage->targetPart = targetPart;

                targetPart->itemRequest = 0;
                targetPart->statusOrErrorReport = 0;
                targetPart->auxiliaryStatus = 0;
            }
	}
    }
    else if (rr->esr->taskSpecificParameters->which == Z_External_update)
    {
    	Z_IUUpdate *up = rr->esr->taskSpecificParameters->u.update;
	yaz_log (LOG_LOG, "Received DB Update");
	if (up->which == Z_IUUpdate_esRequest)
	{
	    Z_IUUpdateEsRequest *esRequest = up->u.esRequest;
	    Z_IUOriginPartToKeep *toKeep = esRequest->toKeep;
	    Z_IUSuppliedRecords *notToKeep = esRequest->notToKeep;
	    
	    yaz_log (LOG_LOG, "action");
	    if (toKeep->action)
	    {
		switch (*toKeep->action)
		{
		case Z_IUOriginPartToKeep_recordInsert:
		    yaz_log (LOG_LOG, " recordInsert");
		    break;
		case Z_IUOriginPartToKeep_recordReplace:
		    yaz_log (LOG_LOG, " recordReplace");
		    break;
		case Z_IUOriginPartToKeep_recordDelete:
		    yaz_log (LOG_LOG, " recordDelete");
		    break;
		case Z_IUOriginPartToKeep_elementUpdate:
		    yaz_log (LOG_LOG, " elementUpdate");
		    break;
		case Z_IUOriginPartToKeep_specialUpdate:
		    yaz_log (LOG_LOG, " specialUpdate");
		    break;
		default:
		    yaz_log (LOG_LOG, " unknown (%d)", *toKeep->action);
		}
	    }
	    if (toKeep->databaseName)
	    {
		yaz_log (LOG_LOG, "database: %s", toKeep->databaseName);
		if (!strcmp(toKeep->databaseName, "fault"))
		{
		    rr->errcode = 109;
		    rr->errstring = toKeep->databaseName;
		}
		if (!strcmp(toKeep->databaseName, "accept"))
		    rr->errcode = -1;
	    }
	    if (toKeep)
	    {
		Z_External *ext = (Z_External *)
                    odr_malloc (rr->stream, sizeof(*ext));
		Z_IUOriginPartToKeep *keep = (Z_IUOriginPartToKeep *)
                    odr_malloc (rr->stream, sizeof(*keep));
		Z_IUTargetPart *targetPart = (Z_IUTargetPart *)
		    odr_malloc (rr->stream, sizeof(*targetPart));

		rr->taskPackage = (Z_TaskPackage *)
                    odr_malloc (rr->stream, sizeof(*rr->taskPackage));
		rr->taskPackage->packageType =
		    odr_oiddup (rr->stream, rr->esr->packageType);
		rr->taskPackage->packageName = 0;
		rr->taskPackage->userId = 0;
		rr->taskPackage->retentionTime = 0;
		rr->taskPackage->permissions = 0;
		rr->taskPackage->description = 0;
		rr->taskPackage->targetReference = (Odr_oct *)
		    odr_malloc (rr->stream, sizeof(Odr_oct));
		rr->taskPackage->targetReference->buf =
		    (unsigned char *) odr_strdup (rr->stream, "123");
		rr->taskPackage->targetReference->len =
		    rr->taskPackage->targetReference->size =
		    strlen((char *) (rr->taskPackage->targetReference->buf));
		rr->taskPackage->creationDateTime = 0;
		rr->taskPackage->taskStatus = odr_intdup(rr->stream, 0);
		rr->taskPackage->packageDiagnostics = 0;
		rr->taskPackage->taskSpecificParameters = ext;

		ext->direct_reference =
		    odr_oiddup (rr->stream, rr->esr->packageType);
		ext->indirect_reference = 0;
		ext->descriptor = 0;
		ext->which = Z_External_update;
		ext->u.update = (Z_IUUpdate *)
		    odr_malloc (rr->stream, sizeof(*ext->u.update));
		ext->u.update->which = Z_IUUpdate_taskPackage;
		ext->u.update->u.taskPackage =  (Z_IUUpdateTaskPackage *)
		    odr_malloc (rr->stream, sizeof(Z_IUUpdateTaskPackage));
		ext->u.update->u.taskPackage->originPart = keep;
		ext->u.update->u.taskPackage->targetPart = targetPart;

		keep->action = (int *) odr_malloc (rr->stream, sizeof(int));
		*keep->action = *toKeep->action;
		keep->databaseName =
		    odr_strdup (rr->stream, toKeep->databaseName);
		keep->schema = 0;
		keep->elementSetName = 0;
		keep->actionQualifier = 0;

		targetPart->updateStatus = odr_intdup (rr->stream, 1);
		targetPart->num_globalDiagnostics = 0;
		targetPart->globalDiagnostics = (Z_DiagRec **) odr_nullval();
		targetPart->num_taskPackageRecords = 1;
		targetPart->taskPackageRecords = 
                    (Z_IUTaskPackageRecordStructure **)
                    odr_malloc (rr->stream,
                                sizeof(Z_IUTaskPackageRecordStructure *));
		targetPart->taskPackageRecords[0] =
                    (Z_IUTaskPackageRecordStructure *)
                    odr_malloc (rr->stream,
                                sizeof(Z_IUTaskPackageRecordStructure));
                
		targetPart->taskPackageRecords[0]->which =
                    Z_IUTaskPackageRecordStructure_record;
		targetPart->taskPackageRecords[0]->u.record = 
                    z_ext_record (rr->stream, VAL_SUTRS, "test", 4);
		targetPart->taskPackageRecords[0]->correlationInfo = 0; 
		targetPart->taskPackageRecords[0]->recordStatus =
                    odr_intdup (rr->stream,
                                Z_IUTaskPackageRecordStructure_success);  
		targetPart->taskPackageRecords[0]->num_supplementalDiagnostics
                    = 0;

		targetPart->taskPackageRecords[0]->supplementalDiagnostics = 0;
            }
	    if (notToKeep)
	    {
		int i;
		for (i = 0; i < notToKeep->num; i++)
		{
		    Z_External *rec = notToKeep->elements[i]->record;

		    if (rec->direct_reference)
		    {
			struct oident *oident;
			oident = oid_getentbyoid(rec->direct_reference);
			if (oident)
			    yaz_log (LOG_LOG, "record %d type %s", i,
				     oident->desc);
		    }
		    switch (rec->which)
		    {
		    case Z_External_sutrs:
			if (rec->u.octet_aligned->len > 170)
			    yaz_log (LOG_LOG, "%d bytes:\n%.168s ...",
				     rec->u.sutrs->len,
				     rec->u.sutrs->buf);
			else
			    yaz_log (LOG_LOG, "%d bytes:\n%s",
				     rec->u.sutrs->len,
				     rec->u.sutrs->buf);
                        break;
		    case Z_External_octet        :
			if (rec->u.octet_aligned->len > 170)
			    yaz_log (LOG_LOG, "%d bytes:\n%.168s ...",
				     rec->u.octet_aligned->len,
				     rec->u.octet_aligned->buf);
			else
			    yaz_log (LOG_LOG, "%d bytes\n%s",
				     rec->u.octet_aligned->len,
				     rec->u.octet_aligned->buf);
		    }
		}
	    }
	}
    }
    else if (rr->esr->taskSpecificParameters->which == Z_External_update0)
    {
	yaz_log(LOG_LOG, "Received DB Update (version 0)");
    }
    else
    {
        yaz_log (LOG_WARN, "Unknown Extended Service(%d)",
		 rr->esr->taskSpecificParameters->which);
	
    }
    return 0;
}

/* result set delete */
int ztest_delete (void *handle, bend_delete_rr *rr)
{
    if (rr->num_setnames == 1 && !strcmp (rr->setnames[0], "1"))
	rr->delete_status = Z_DeleteStatus_success;
    else
        rr->delete_status = Z_DeleteStatus_resultSetDidNotExist;
    return 0;
}

/* Our sort handler really doesn't sort... */
int ztest_sort (void *handle, bend_sort_rr *rr)
{
    rr->errcode = 0;
    rr->sort_status = Z_SortStatus_success;
    return 0;
}


/* present request handler */
int ztest_present (void *handle, bend_present_rr *rr)
{
    return 0;
}

/* retrieval of a single record (present, and piggy back search) */
int ztest_fetch(void *handle, bend_fetch_rr *r)
{
	char query[100];
	
    r->errstring = 0;
    r->last_in_set = 0;
    r->basename = database;
    r->output_format = r->request_format;  
    
    
    if (cur_results>=n_results) {
      r->errcode=13;
      return 0;
    } else {
    	sprintf(query,"query=%s&command=get_notice",ids[cur_results]);
    	cur_results++;
    	if (cur_results==n_results) 
    		r->last_in_set=1;	
    		
      	if (http_get(query)) {	
			r->errcode=2;
			return 0;
      	}
    }
    yaz_log(LOG_LOG,"sending notice for notice_id %s",ids[cur_results-1]);
    r->len = strlen(http_content);
    r->record = http_content;
    r->output_format = VAL_UNIMARC;
    r->errcode = 0;
    return 0;
}

/*
 * no scan allowed
 */

int ztest_scan(void *handle, bend_scan_rr *q)
{
	/*Nothing to do*/
    return 0;
}

static int ztest_explain(void *handle, bend_explain_rr *rr)
{
    if (rr->database && !strcmp(rr->database, "Default"))
    {
	rr->explain_buf = "<explain>\n"
	    "\t<serverInfo>\n"
	    "\t\t<host>localhost</host>\n"
	    "\t\t<port>210</port>\n"
	    "\t</serverInfo>\n"
	    "</explain>\n";
    }
    return 0;
}

int read_conf_file() {
	statserv_options_block *options;
	FILE *configfile;
	char param[100];
	char value[100];
	char *param1;
	char *value1;
	char line[201];
	int flagparam;
	int err;
	
	err=0;
	
	options = statserv_getcontrol();	
	
	  //Lecture des paramètres
    configfile = fopen(options->configname,"r");
    if (configfile==NULL) {
    	return 1;
    }
    while (!feof(configfile)) {
    	fgets(line,4096,configfile);
    	if (line[strlen(line)-1]==13) line[strlen(line)-1]=0x00;
    	if (line[strlen(line)-1]==10) line[strlen(line)-1]=0x00;
    	param1 = strtok(line,"=");
    	if (param1!=NULL) {
    		strcpy(param,param1);
    		value1=strtok(NULL,"=");
    		if (value1==NULL) {
    			if (param[0]!=35) {
  					return 1;
    			}
    		} else {
    			strcpy(value,value1);
    			flagparam=0;
    			yaz_log(LOG_LOG,"param %s %s",param,value);
    			//webpmb_host
    			if (!strcmp(param,"webpmb_host")) {
    				strcpy(server_name,value);
    				flagparam=1;
    			}
    			//webpmb_port
    			if (!strcmp(param,"webpmb_port")) {
    				port=atoi(value);
    				flagparam=1;
    			}
    			//webpmb_script
    			if (!strcmp(param,"webpmb_script")) {
    				strcpy(request_path,value);
    				flagparam=1;
    			}
    			//z3950_database
    			if (!strcmp(param,"z3950_database")) {
    				strcpy(database,value);
    				flagparam=1;
    			}
    			if (flagparam==0) {
    				return 1;
    			}
    		}
    	}
    }
    fclose(configfile);
    return 0;
}

bend_initresult *bend_init(bend_initrequest *q)
{
    bend_initresult *r = (bend_initresult *)
        odr_malloc (q->stream, sizeof(*r));
    int *counter = (int *) xmalloc (sizeof(int));

    *counter = 0;
    r->errcode = 0;
    r->errstring = 0;
    r->handle = counter;         /* user handle, in this case a simple int */
    q->bend_sort = ztest_sort;              /* register sort handler */
    q->bend_search = ztest_search;          /* register search handler */
    q->bend_present = ztest_present;        /* register present handle */
    q->bend_esrequest = ztest_esrequest;
    q->bend_delete = ztest_delete;
    q->bend_fetch = ztest_fetch;
    q->bend_scan = ztest_scan;
    q->bend_explain = ztest_explain;
    
    if (read_conf_file()) {
    	yaz_log(LOG_LOG,"Can't handle configuration file");
    	r->errcode = 2;
    }
     return r;
}

void bend_close(void *handle)
{
    xfree (handle);              /* release our user-defined handle */
    return;
}

int main(int argc, char **argv)
{
    return statserv_main(argc, argv, bend_init, bend_close);
}
