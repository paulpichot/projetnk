// --------------------------------------------------
// 🧰 Tools
// --------------------------------------------------

import esbuild from "esbuild";
import browserSync from "browser-sync";

// --------------------------------------------------
// 🔧 Setup
// --------------------------------------------------

const isBuildMode = process.argv[2] === 'build';

// Setting the valet domain by appending .test to the directory name
const directoryPath = process.cwd(); 
const directoryName = directoryPath.split("/").pop();
const domain = `${directoryName}.test`;

// --------------------------------------------------
// ⚡ BrowserSync
// --------------------------------------------------

const browserSyncInstance = browserSync.create();

if (!isBuildMode) {
  browserSyncInstance.init({
    proxy: domain,
    host: domain,
    reloadOnRestart: true,
    notify: false,
    ui: false,
  });
}

// --------------------------------------------------
// ✨ JavaScript
// Bundles and minifies JavaScript files
// --------------------------------------------------

const jsConfig = {
  entryPoints: ["assets/js/index.js"],
  outfile: "assets/main.min.js",
  minify: true,
  bundle: true,
  sourcemap: !isBuildMode,
  loader: {
    '.png': 'file',
    '.svg': 'file'
  }
};

// --------------------------------------------------
// 🎨 CSS
// Compiles and minifies CSS files
// --------------------------------------------------

const cssConfig = {
  entryPoints: ["assets/css/index.css"],
  outfile: "assets/main.min.css",
  minify: true,
  bundle: true,
  sourcemap: !isBuildMode,
  loader: {
    '.css': 'css',
    '.svg': 'file',
    '.png': 'file'
  },
  target: ["chrome88", "firefox78", "safari14"],
  external: ['*.woff', '*.woff2', '*.ttf', '*.eot', '*.otf', '*.svg', '*.png', '*.webp'],
};

// Function to build or watch files
const processFiles = async (config, type) => {
  if (isBuildMode) {
    try {
      await esbuild.build(config);
      console.log(`${type} files built successfully`);
    } catch (error) {
      console.error(`Error building ${type} files:`, error);
    }
  } else {
    try {
      const context = await esbuild.context(config);
      await context.watch();
      console.log(`Watching ${type} files...`);
    } catch (error) {
      console.error(`Error setting up watch for ${type} files:`, error);
    }
  }
};

// Process JS and CSS files
await processFiles(jsConfig, "JavaScript");
await processFiles(cssConfig, "CSS");

// --------------------------------------------------
// 📁 Other files
// --------------------------------------------------

if (!isBuildMode) {
  browserSyncInstance.watch([
    "assets/*.css",
    "assets/*.js",
    "assets/images/**",
    "site/*/**",
    "!site/sessions/**",
    "!site/cache/**",
    "content/**/*.*"
  ]).on("change", (file) => {
    browserSyncInstance.reload(file);
  });
}