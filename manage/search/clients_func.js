function drop(custom_id){
	if (window.confirm("削除します。\n宜しいですか？")) {
		location.href = "/manage/search/delete.php?id="+custom_id;
	}
}
function edit(custom_id){
	location.href = "/manage/clients/edit?id="+custom_id;
}
