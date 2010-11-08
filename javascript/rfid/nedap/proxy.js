
    // javascript proxy for webservices
    // by Matthias Hertel
    /*A NedapRfidReader Access Service*/
     // url: "http://127.0.0.1/nedaprfidwebservice/nedaprfidwebservice.asmx",
     proxies.NedapRfidWebService = {
     url: url_serveur_rfid,
     ns: "http://www.nedaplibrary.com/NedapRfidWebService/"
     } // proxies.NedapRfidWebService
     
        /** Polls for labels that are currently in the field, 
         * reads the data and determines their types. 
         * Ipaddress is the network address of the reader that needs to be polled. 
         * Timeout is the maximum timeout that the webservice can wait for new incoming labels. 
         * ReadData set to false only returns the UID of all the labels present, 
         * ReadData set to true returns the interpreted data contents */
       
       proxies.NedapRfidWebService.readLabel 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.readLabel.fname
        = "readLabel";
       proxies.NedapRfidWebService.readLabel.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.readLabel.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/readLabel";
       proxies.NedapRfidWebService.readLabel.params
        = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "ReadData"
        ];
      proxies.NedapRfidWebService.
         readLabel.rtype 
         = [
          "RfidLabels","UID","DocumentNumber","Usage"
        ];
    
        /** Enables the EAS bit of the label with the given UID. Returns true if successfull, False if it failed */
       
       proxies.NedapRfidWebService.EnableEAS 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.EnableEAS.fname
        = "EnableEAS";
       proxies.NedapRfidWebService.EnableEAS.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.EnableEAS.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/EnableEAS";
       proxies.NedapRfidWebService.EnableEAS.params
        = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "UID"
        ];
      proxies.NedapRfidWebService.
         EnableEAS.rtype 
         = [
          "EnableEASResult"
        ];
    
        /** Disables the EAS bit of the label with the given UID. Returns true if successfull, False if it failed */
       
       proxies.NedapRfidWebService.DisableEAS 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.DisableEAS.fname
        = "DisableEAS";
       proxies.NedapRfidWebService.DisableEAS.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.DisableEAS.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/DisableEAS";
       proxies.NedapRfidWebService.DisableEAS.params
        = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "UID"
        ];
      proxies.NedapRfidWebService.
         DisableEAS.rtype 
         = [
          "DisableEASResult"
        ];
    
       
        /** Writes data */
       
        /** Writes data according to the NedapLabel dataform (barcode-only in hexstring) */
       
        /** Writes data according to the NBD label format v4.1 */
       
        /** Writes data according to the FrenchLabel dataform FR01. LogisticPartGroup5 can not be written in this function */
       
        /** Writes data according to the FrenchLabel dataform FR01 */
       
        /** Writes data according to the DanishLabel dataform of july 2005 */
   
        
    
       proxies.NedapRfidWebService.WriteFrenchLabel_native 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.WriteFrenchLabel_native.fname
        = "WriteFrenchLabel_native";
       proxies.NedapRfidWebService.WriteFrenchLabel_native.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.WriteFrenchLabel_native.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/WriteFrenchLabel_native";
       proxies.NedapRfidWebService.WriteFrenchLabel_native.params  
       = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "UID"
        ,
          "DocumentNumber"
 		,
          "LibraryCode"
 		,
          "ItemNr:int"
		,
          "TotalItems:int"
		,
          "Usage:int"      
		,
          "TypeEAS:int",
          "LogisticPartGroup1",
          "LogisticPartGroup2",
          "LogisticPartGroup3",
          "LogisticPartGroup4",
          "LogisticPartGroup5"
        ];
      proxies.NedapRfidWebService.WriteFrenchLabel_native.rtype 
         = [
          "WriteFrenchLabel_nativeResult"
        ];
        
        /** Enables the EAS bit of the label with the given Barcode. Returns true if successfull, False if it failed */
       
       proxies.NedapRfidWebService.EnableBarcodeEAS 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.EnableBarcodeEAS.fname
        = "EnableBarcodeEAS";
       proxies.NedapRfidWebService.EnableBarcodeEAS.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.EnableBarcodeEAS.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/EnableBarcodeEAS";
       proxies.NedapRfidWebService.EnableBarcodeEAS.params
        = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "Barcode"
        ];
      proxies.NedapRfidWebService.
         EnableBarcodeEAS.rtype 
         = [
          "EnableBarcodeEASResult"
        ];
    
        /** Disables the EAS bit of the label with the given Barcode. Returns true if successfull, False if it failed */
       
       proxies.NedapRfidWebService.DisableBarcodeEAS 
        = function () { return(proxies.callSoap(arguments)); }
       proxies.NedapRfidWebService.DisableBarcodeEAS.fname
        = "DisableBarcodeEAS";
       proxies.NedapRfidWebService.DisableBarcodeEAS.service
        = proxies.NedapRfidWebService;
       proxies.NedapRfidWebService.DisableBarcodeEAS.action
        = "http://www.nedaplibrary.com/NedapRfidWebService/DisableBarcodeEAS";
       proxies.NedapRfidWebService.DisableBarcodeEAS.params
        = [
          "ipaddress"
        ,
          "timeout:int"
        ,
          "Barcode"
        ];
      proxies.NedapRfidWebService.
         DisableBarcodeEAS.rtype 
         = [
          "DisableBarcodeEASResult"
        ];
    
    