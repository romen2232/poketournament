import { defineConfig, devices } from "@playwright/test";

export default defineConfig({
  timeout: 30000,
  testDir: "tests/e2e",
  projects: [
    {
      name: "chromium",
      use: { ...devices["Desktop Chrome"] },
    },
  ],
  use: {
    baseURL: "http://nextjs:3000",
    headless: true,
    ignoreHTTPSErrors: true,
  },
});


