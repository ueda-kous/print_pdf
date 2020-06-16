<?php
/*********************************************
 * Groweb ASP
 * @generate_pdf.php
 * @author: Hirao
 * @ver:
 * 2018/05/23 改変初版
 * commnet:
PDFレポートの生成コマンドを発生するプログラム。
*********************************************/

if(empty($_GET)){

	// 変数なしのコールはエラー表示
	echo "[Error] 処理に必要な情報が受信できませんでした。";
	return false;

}else{

	// 顧客ID
	$customer_id = htmlspecialchars($_GET['c_id']);

	// 代理店ID
	$agency_id = htmlspecialchars($_GET["a_id"]);

	// アナリティクスのViewID
	$view_id = htmlspecialchars($_GET['v_id']);

	// データベース上のサイトID
	$site_id = htmlspecialchars($_GET['s_id']);

	// 出力対象となるレポート年月
	$target_date = htmlspecialchars($_GET['date']);

	// 生成するPDFファイル名
	$pdf_filename = htmlspecialchars($_GET['file']);

	// レポート描写を担当するPHPファイルのURL
	$call_url = htmlspecialchars($_GET["call_url"]);


	// レポート生成モード
	// ac = AC用レポート
	// ip = 企業IPレポート
	// year = 年間レポート
	// 上記以外は標準レポート
	$report_type = htmlspecialchars($_GET["report_type"]);

	// v4レポートの生成
	$flg_version = htmlspecialchars($_GET["flg_version"]);

	// 呼び出しURLの生成
	$call_url .= "/report_convert/index_json.php";
	$call_url .= "?";
	$call_url .= "customer_id={$customer_id}";
	$call_url .= "&";
	$call_url .= "agency_id={$agency_id}";
	$call_url .= "&";
	$call_url .= "site_id={$site_id}";
	$call_url .= "&";
	$call_url .= "view_id={$view_id}";
	$call_url .= "&";
	$call_url .= "target_date={$target_date}";
	$call_url .= "&";
	$call_url .= "report_type={$report_type}";


	// PDF生成コマンドの設定
	$cmd = "wkhtmltopdf --page-size A4 --orientation Landscape --margin-top 0 --margin-bottom 0 --margin-left 0 --margin-right 0 \"{$call_url}\" {$pdf_filename}.pdf";


	// PDF生成コマンドの設定
	//$cmd = "wkhtmltopdf --page-size A4 --orientation Landscape --margin-top 0 --margin-bottom 0 --margin-left 0 --margin-right 0 \"http://{$targetURL}index_json.php?customer_id={$customer_id}&site={$site_id}&view_id={$view_id}&date={$target_date}&ip={$ip}&ac={$ac}&fname={$pdf_filename}&oem={$agency_id}&flg_version={$flg_version}\" {$pdf_filename}.pdf";

	// PDF生成コマンドを実行する
	system($cmd, $status);

	// 最終的に生成するファイル名を設定する
	$file = $pdf_filename.".pdf";

/*
	if("ip" == $report_type){
		$file .= "_ip";
	}
	if("year" == $report_type){
		$file .= "_year";
	}
	$file .= ".pdf";
*/

	// 生成したPDFファイルの利用権限を設定
	$cmd = "chmod 777 {$file}";
	system($cmd, $status);

	// ファイルが正常に生成されて存在するか確認
	if(file_exists($file)){

		// PDFのファイルサイズがゼロならシステムエラー
		if (($content_length = filesize($file)) == 0) {

			die("Error: File size is 0.(".$file.")");

		}//endif

		// ダウンロードするダイアログを出力
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename={$file}");

		// ファイルを読んで削除しておく。
		if (!readfile($file)) {
			// ファイルを読み込めなければシステムエラー
			die("Cannot read the file(".$file.")");
		}else{
			// 一時的に生成している内部のPDFファイルを削除
			exec('rm *.pdf');
		}//endif

	}else{
		echo "[Error] PDFファイルが正常に生成できませんでした。";
		return false;
	}//endif

}//endif
?>
