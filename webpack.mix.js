const mix = require("laravel-mix");

mix
  .js("src/frontend/main.js", "assets/frontend")
  .js("src/admin/main.js", "assets/admin")
  .vue()
  .postCss("src/frontend/main.css", "assets/frontend", [
    require("tailwindcss"),
    require("autoprefixer"),
  ])
  .postCss("src/admin/main.css", "assets/admin", [
    require("tailwindcss"),
    require("autoprefixer"),
  ]);
