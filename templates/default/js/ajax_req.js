
function GetXmlHttpObject(handler)
{
  
  var objXMLHttp=null
  if (window.XMLHttpRequest)
  {
      objXMLHttp=new XMLHttpRequest()
  }
  else if (window.ActiveXObject)
  {
      objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
  return objXMLHttp
}

function stateChanged()
{
  if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
  {
          // txtResult will be filled with new page
          
          
        
          document.getElementById("txtResult").innerHTML = xmlHttp.responseText
    
 
  }
  else {
          //alert(xmlHttp.status);
        
  }
}



function htmlData(url, qStr)
{
  
   
  
  if (url.length==0)
  {
      document.getElementById("txtResult").innerHTML = "";
      return;
  }

  xmlHttp = GetXmlHttpObject();

  if (xmlHttp==null)
  {
      alert ("Browser does not support HTTP Request");
      return;
  }

  url=url+"?"+qStr;
  url=url+"&sid="+Math.random();
  xmlHttp.onreadystatechange = stateChanged;
  xmlHttp.open("GET",url,true) ;
  
  xmlHttp.send(null);
}


function htmlData(url, qStr)
{
  
  
  
  if (url.length==0)
  {
      document.getElementById("txtResult").innerHTML = "";
      return;
  }

  xmlHttp = GetXmlHttpObject();

  if (xmlHttp==null)
  {
      alert ("Browser does not support HTTP Request");
      return;
  }

  url=url+"?"+qStr;
  url=url+"&sid="+Math.random();
  xmlHttp.onreadystatechange = stateChanged;
  xmlHttp.open("GET",url,true) ;
  
  xmlHttp.send(null);
}




