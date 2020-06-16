const puppeteer = require('puppeteer');

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
  await page.goto('https://site-analytics.jp/report_convert/index_json.php?test=1');
  await loadPromise;

  // PDF作成処理
  await page.pdf({
    path: './pdf/groweb.pdf',
    displayHeaderFooter: true,
    printBackground: true,
    format: 'A4',
    landscape: true,
  });

  browser.close();
  console.log('PDF出力完了');
})();