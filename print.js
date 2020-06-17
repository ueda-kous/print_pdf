// 引数 URL ファイル名

const puppeteer = require('puppeteer');
const targetUrl = process.argv[2];
const fileName  = process.argv[3];

(async() => {
  const browser = await puppeteer.launch({
    args: [
        '--no-sandbox',
        '--disable-setuid-sandbox'
      ]
  });
  const page = await browser.newPage();

  // PDF出力対象ページ
  let loadPromise = page.waitForNavigation();
//   await page.goto('https://site-analytics.jp/report_convert/index_json.php?test=1');
  await page.goto(targetUrl);
  await loadPromise;

  // PDF作成処理
  await page.pdf({
    path: './'+fileName+'.pdf',
    displayHeaderFooter: true,
    printBackground: true,
    format: 'A4',
    landscape: true,
  });

  browser.close();
  console.log('PDF出力完了');
})();