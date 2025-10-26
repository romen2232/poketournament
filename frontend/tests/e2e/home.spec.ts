import { test, expect } from "@playwright/test";

test("home page renders", async ({ page }) => {
  await page.goto("/", { waitUntil: "domcontentloaded" });
  await expect(page.locator("text=PokeTournament")).toBeVisible();
});


