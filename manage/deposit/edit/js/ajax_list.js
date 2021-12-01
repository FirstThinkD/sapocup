function myFnc(index){
	// alert("ダブルクリックされました。" + index);
	// return false;

	$.ajax({
		// リクエスト方法
		type: "GET",
		// 送信先ファイル名
		url: "/manage/deposit/edit/js/ajax_list_show.php",
		// 受け取りデータの種類
		datatype: "json",
		// 送信データ
		data:{
			// #ajax_showのvalueをセット
			"id" : index,
		},
		// 通信が成功した時
		success: function(data) {
			$('#dl_id2 input').val();
			$('#dl_date2 input').val();
			$('#dl_money2 input').val();
			$('#dl_comment2 input').val();
			$('#dl_id2 input').val(data[0].dl_id);
			$('#dl_date2 input').val(data[0].dl_yymmdd);
			$('#dl_money2 input').val(data[0].dl_money);
			$('#dl_comment2 input').val(data[0].dl_comment);

			console.log(data[0]);
			console.log("通信成功");
			// console.log(data);
		},

		// 通信が失敗した時
		error: function(data) {
			console.log("通信失敗");
			// console.log(data);
		}
	});

	return false;
}