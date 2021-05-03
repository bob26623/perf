function doCallAjax(myid,url,para) {
                  HttPRequest = false;
                  if (window.XMLHttpRequest) { // Mozilla, Safari,...
                         HttPRequest = new XMLHttpRequest();
                         if (HttPRequest.overrideMimeType) {
                                HttPRequest.overrideMimeType('text/html');
                         }
                  } else if (window.ActiveXObject) { // IE
                         try {
                                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
                         } catch (e) {
                                try {
                                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                                } catch (e) {}
                         }
                  } 
                  
                  if (!HttPRequest) {
                         alert('Cannot create XMLHTTP instance');
                         return false;
                  }
                    var myvalue =  document.getElementById(para);
                    var pmeters = "PARA="+ myvalue.value;

                        HttPRequest.open('POST',url,true);

                        HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        HttPRequest.setRequestHeader("Content-length", pmeters.length);
                        HttPRequest.setRequestHeader("Connection", "close");
                        HttPRequest.send(pmeters);
                        
                        //*** Loading (Client -> Server) ***//
                        //document.getElementById("imgLoading").style.display = '';
                        //document.getElementById(myid).style.display = 'none';
                        
                        HttPRequest.onreadystatechange = function()
                        {

                                 if(HttPRequest.readyState == 3)  // Loading Request
                                  {
                                   document.getElementById("myid").innerHTML = "Now is Loading...";
                                        //*** Loading ***//
                                   //document.getElementById("imgLoading").style.display = '';
                                   //document.getElementById(myid).style.display = 'none';
                                  }

                                 if(HttPRequest.readyState == 4) // Return Request
                                  {                       
                                                //document.getElementById("imgLoading").style.display = 'none';
						document.getElementById(myid).style.display = '';
                        			document.getElementById(myid).innerHTML = HttPRequest.responseText;
                                  }                             
                        }

           }
