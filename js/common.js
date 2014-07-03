function  validateCheckGroup(ele)
{
	var a=new Array();
	a=document.getElementsByName(ele);
	var p=0;
	for(i=0;i<a.length;i++){
		if(a[i].checked){
			p=1;
		}
	}
	if (p==0){
		alert('Please select at least one check box.');
		return false;
	}
	return true;
}