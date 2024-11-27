const puppeteer = require('puppeteer');
const path = require('path');

(async () => {
    const NUM_VISITS = 50;

    const TARGET_HTML = `file://${path.resolve(__dirname, 'site1.html')}`;

    console.log(`Simulating ${NUM_VISITS} visits to ${TARGET_HTML}`);

    console.log('Launching Puppeteer...');
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });

    const page = await browser.newPage();

    for (let i = 0; i < NUM_VISITS; i++) {
        console.log(`Visiting page (${i + 1}/${NUM_VISITS})`);
        await page.goto(TARGET_HTML, { waitUntil: 'domcontentloaded' });

        console.log(`Simulated visit ${i + 1}`);
        await page.waitForTimeout(1500);
    }

    console.log('Closing Puppeteer...');
    await browser.close();
})();
