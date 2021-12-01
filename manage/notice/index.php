<?php
session_start();
require_once(__DIR__ . '/../../common/dbconnect.php');
require_once(__DIR__ . '/../common/functions.php');


// ログイン確認
if (empty($_SESSION['loginID'])) {
	header("Location:/login.php");
	exit();
}

/*-----------------------------------------------------------

	リスト一覧表示

-------------------------------------------------------------*/

$result = array();
$ix = 0;

///////////////////////////////////////////////////////////////////////////
// 請求書受渡期日が3日後に近付いています
$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND invoice_flag=1 AND delFlag=0 AND qu_deliveryDate>="%s" AND qu_deliveryDate<="%s"',
    mysqli_real_escape_string($db, $_SESSION['loginID']),
    mysqli_real_escape_string($db,date("Y-m-d",time())),
    mysqli_real_escape_string($db,date("Y-m-d",time()+3*86400))
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
// DB情報取得
while ($row0 = mysqli_fetch_assoc($record)) {
    $is_new  = ($row0['notice_flag']==0);
    $result[$ix++] = array(
        'is_new'=>$is_new,
        'u_id'=>$row0['u_id'],
        'qu_id'=>$row0['qu_id'],
        'date'=>date("Y/m/d",strtotime($row0['qu_deliveryDate'])-3*86400),
        'link'=>'/manage/invoices/edit/?new=1&id='.$row0['qu_id'],
        'content'=>$row0['in_companyName'].'様請求書受渡期日が3日後に近付いています',
    );
}


///////////////////////////////////////////////////////////////////////////
// 入金消込の期限（お客様お支払い期限）が後3日に近付いています
$sql = sprintf('SELECT * FROM `quotation` WHERE u_id="%d" AND delFlag=0 AND qu_paymentDate>="%d" AND qu_paymentDate<="%d" AND qu_startDate<="%s" AND qu_endDate>="%s" ',
    mysqli_real_escape_string($db, $_SESSION['loginID']),
    mysqli_real_escape_string($db,date("d",time())),
    mysqli_real_escape_string($db,date("d",time()+3*86400)),
    mysqli_real_escape_string($db,date("Y-m-d",time()+3*86400)),
    mysqli_real_escape_string($db,date("Y-m-d",time()+3*86400))
);
$record = mysqli_query($db, $sql) or die(mysqli_error($db));
// DB情報取得
while ($row0 = mysqli_fetch_assoc($record)) {
    $is_new  = ($row0['notice_flag']==0);
    $result[$ix++] = array(
        'is_new'=>$is_new,
        'u_id'=>$row0['u_id'],
        'qu_id'=>$row0['qu_id'],
        'link'=>'/manage/deposit/edit/?new=1&id='.$row0['qu_id'],
        'date'=>date('Y/m/d',strtotime(date("Y/m/").$row0['qu_paymentDate'])-3*86400),
        'content'=>$row0['in_companyName'].'様入金消込の期限が後3日に近付いています',
    );
}
//Sort date
function cmp($a, $b){
    if ($a['date'] == $b['date']) {
        return 0;
    }
    return ($a['date'] < $b['date']) ? 1 : -1;
}

usort($result, "cmp");

///////////////////////////////////////////////////////////////////////////
//Update notice_flag
$sql = sprintf('UPDATE quotation SET notice_flag=0 WHERE u_id="%s" AND notice_flag=1',
    mysqli_real_escape_string($db, $_SESSION['loginID'])
);
//mysqli_query($db, $sql) or die(mysqli_error($db));

?>

<?php require_once(__DIR__ . '../../common/header.php'); ?>
	<main class="customer_data">
		<div class="main_inner">
			<?php require_once(__DIR__ .'../../common/grobal-menu.php'); ?>
			<div class="main_wrap">
				<div class="main_title">
					<div class="all_wrapper sp_all">
						<div class="main_title_inner">
							<div class="main_title_top">
								<p class="title">お知らせ</p>
							</div>
						</div>
					</div>
				</div>
				<div class="main_content">
					<div class="all_wrapper sp_all">
						<div class="main_content_inner">
							<form action="" method="post" enctype="multipart/form-data">
								<div class="main_content_wrap">
									<table class="common_table1 notice_table">
										<tbody class="common_table_tbody">

                                        <?php foreach($result as $row) { ?>
                                        <tr class="common_table_tr">
                                            <td class="common_table_cell">
                                                <?php echo $row['date'];?>
                                            </td>
                                            <td class="">
                                                <?php
                                                //アップデートの内容について
                                                //入金消込の期限（お客様お支払い期限）が後3日に近付いています
                                                //請求書受渡期日が3日後に近付いています
                                                echo '<a href="'.$row['link'].'">';
                                                if($row['is_new']){
                                                    echo $row['content'].'<span class="new_notice">NEW</span>';
                                                }else{
                                                    echo $row['content'];
                                                }
                                                echo '</a>';
                                                ?>
                                            </td>
                                        </tr>
                                        <?php }?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="/manage/common/js/customer-data.js"></script>
<!--	<script>-->
<!--		// sinclo-->
<!--		$(function() {-->
<!--			$('#chatButton').on('click', function(){-->
<!--				$('#sincloBox').toggleClass('chatOpen');-->
<!--				$('#sincloBox').data('true');-->
<!--			})-->
<!--		})-->
<!--	</script>-->
<!--	<script src='https://ws1.sinclo.jp/client/5e7812fdb5a66.js'></script>-->
</body>
</html>

