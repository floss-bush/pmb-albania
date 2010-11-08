// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: proxy.js,v 1.2 2009-07-21 09:36:35 ngantier Exp $

    // javascript proxy for webservices
    // by Matthias Hertel
    // url_serveur_rfid  ou bien "http://192.168.0.26/Rfid%20Web%20Services/RfidWebServices.asmx"
    
     proxies.RfidWebServices = {
     url: url_serveur_rfid,
     ns: "http://identag.net/"
     } // proxies.RfidWebServices
     
        /** Get information on each element of the sub-system */
       
       proxies.RfidWebServices.GetInfo 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.GetInfo.fname
        = "GetInfo";
       proxies.RfidWebServices.GetInfo.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.GetInfo.action
        = "http://identag.net/GetInfo";
       proxies.RfidWebServices.GetInfo.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         GetInfo.rtype 
         = [
          "GetInfoResult:int"
        ,
          "astrReturnInfos"
        ];
    
        /** RTo be used to know is a specified feature is supported. */
       
       proxies.RfidWebServices.IsFeatureSupported 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.IsFeatureSupported.fname
        = "IsFeatureSupported";
       proxies.RfidWebServices.IsFeatureSupported.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.IsFeatureSupported.action
        = "http://identag.net/IsFeatureSupported";
       proxies.RfidWebServices.IsFeatureSupported.params
        = [
          "strConnectionString"
        ,
          "byFeature"
        ,
          "bIsSupported"
        ];
      proxies.RfidWebServices.
         IsFeatureSupported.rtype 
         = [
          "IsFeatureSupportedResult:int"
        ,
          "bIsSupported"
        ];
    
        /** Return the last raised error. Return 0 if the last call succeeded. Return a negative value if an error occured. */
       
       proxies.RfidWebServices.GetLastError 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.GetLastError.fname
        = "GetLastError";
       proxies.RfidWebServices.GetLastError.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.GetLastError.action
        = "http://identag.net/GetLastError";
       proxies.RfidWebServices.GetLastError.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         GetLastError.rtype 
         = [
          "GetLastErrorResult:int"
        ,
          "strLastError"
        ];
    
        /** Activate or deactivate the EAS (=anti-theft system) of all tags found in the RF field. */
       
       proxies.RfidWebServices.SetAllEas 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.SetAllEas.fname
        = "SetAllEas";
       proxies.RfidWebServices.SetAllEas.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.SetAllEas.action
        = "http://identag.net/SetAllEas";
       proxies.RfidWebServices.SetAllEas.params
        = [
          "strConnectionString"
        ,
          "bActivateEas"
        ];
      proxies.RfidWebServices.
         SetAllEas.rtype 
         = [
          "SetAllEasResult:int"
        ];
    
        /** Activate the EAS (=anti-theft system) of the specified tag. The tag to update must already be in the RF field. */
       
       proxies.RfidWebServices.SetEas 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.SetEas.fname
        = "SetEas";
       proxies.RfidWebServices.SetEas.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.SetEas.action
        = "http://identag.net/SetEas";
       proxies.RfidWebServices.SetEas.params
        = [
          "strConnectionString"
        ,
          "strDocumentIdentifier"
        ,
          "bActivateEas"
        ];
      proxies.RfidWebServices.
         SetEas.rtype 
         = [
          "SetEasResult:int"
        ];
    
        /** Read all the tags in the RF field, and return the identifiers of encountered documents and patron cards. */
       
       proxies.RfidWebServices.Read 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.Read.fname
        = "Read";
       proxies.RfidWebServices.Read.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.Read.action
        = "http://identag.net/Read";
       proxies.RfidWebServices.Read.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         Read.rtype 
         = [
          "ReadResult:int"
        ,
          "astrDocumentIdentifiers"
        ,
          "astrPatronIdentifiers"
        ];
 
         /** Read all the tags in the RF field, and return the identifiers of encountered documents and patron cards. */
       
       proxies.RfidWebServices.ReadEx 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.ReadEx.fname
        = "ReadEx";
       proxies.RfidWebServices.ReadEx.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.ReadEx.action
        = "http://identag.net/ReadEx";
       proxies.RfidWebServices.ReadEx.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         ReadEx.rtype 
         = [
          "ReadExResult:int"
        ,
          "aobjItems"
        ,
          "astrPatronIdentifiers"
        ];   
        /** Write the identifier of the specified number of blank tags. If the number o blank tags encountered in the RF field doesn't match with the specified number of tags, write is aborted. The number of blank tag encountered in the RF fied is return in the 'byNumberOfBlankTags' parameter */
       
       proxies.RfidWebServices.WriteDocument 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.WriteDocument.fname
        = "WriteDocument";
       proxies.RfidWebServices.WriteDocument.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.WriteDocument.action
        = "http://identag.net/WriteDocument";
       proxies.RfidWebServices.WriteDocument.params
        = [
          "strConnectionString"
        ,
          "strDocumentIdentifier"
        ,
          "byNumberOfTags"
        ];
      proxies.RfidWebServices.
         WriteDocument.rtype 
         = [
          "WriteDocumentResult:int"
        ,
          "byNumberOfTags"
        ];
    
        /** Write the identifier and some other fields of the specified number of blank tags. If the number o blank tags encountered in the RF field doesn't match with the specified number of tags, write is aborted. The number of blank tag encountered in the RF fied is return in the 'byNumberOfBlankTags' parameter */
       
       proxies.RfidWebServices.WritePatron 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.WritePatron.fname
        = "WritePatron";
       proxies.RfidWebServices.WritePatron.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.WritePatron.action
        = "http://identag.net/WritePatron";
       proxies.RfidWebServices.WritePatron.params
        = [
          "strConnectionString"
        ,
          "strPatronIdentifier"
        ];
      proxies.RfidWebServices.
         WritePatron.rtype 
         = [
          "WritePatronResult:int"
        ];
    
        /** Erase all tags found in the RF field of the antenna. */
       
       proxies.RfidWebServices.EraseAllTags 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.EraseAllTags.fname
        = "EraseAllTags";
       proxies.RfidWebServices.EraseAllTags.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.EraseAllTags.action
        = "http://identag.net/EraseAllTags";
       proxies.RfidWebServices.EraseAllTags.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         EraseAllTags.rtype 
         = [
          "EraseAllTagsResult:int"
        ];
    
        /** Detect either some tags are in the RF field or not. */
       
       proxies.RfidWebServices.DetectTag 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.DetectTag.fname
        = "DetectTag";
       proxies.RfidWebServices.DetectTag.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.DetectTag.action
        = "http://identag.net/DetectTag";
       proxies.RfidWebServices.DetectTag.params
        = [
          "strConnectionString"
        ];
      proxies.RfidWebServices.
         DetectTag.rtype 
         = [
          "DetectTagResult:int"
        ,
          "bOneOrMoreTagsDetected"
        ];
    
        /** Make the Web Services generate the License Request file to be sent to Ident in order to get the Web Services registered. */
       
       proxies.RfidWebServices.GenerateLicenseRequest 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.RfidWebServices.GenerateLicenseRequest.fname
        = "GenerateLicenseRequest";
       proxies.RfidWebServices.GenerateLicenseRequest.service
        = proxies.RfidWebServices;
       proxies.RfidWebServices.GenerateLicenseRequest.action
        = "http://identag.net/GenerateLicenseRequest";
       proxies.RfidWebServices.GenerateLicenseRequest.params
        = [];
      proxies.RfidWebServices.
         GenerateLicenseRequest.rtype 
         = [
          "GenerateLicenseRequestResult:int"
        ,
          "strLicenseRequestFilename"
        ];
    