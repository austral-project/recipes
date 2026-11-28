import path from "node:path";
import Encore from "@symfony/webpack-encore";
// Styles and scripts of the administration: the design system of design-bundle, then those of admin-bundle,
// in the entry "austral-admin" read by austral_admin_styles() and austral_admin_scripts().
import configureAustralAdmin from "./vendor/austral/admin-bundle/encore.mjs";

if (!Encore.isRuntimeEnvironmentConfigured())
{
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

configureAustralAdmin(Encore)
  // Same folder as the option austral_admin.build (default %kernel.project_dir%/public/build/entrypoints.json)
  .setOutputPath("public/build/")
  .setPublicPath("/build")
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

// Add the entries of the project here, e.g. .addEntry("app", "./assets/app.js") before getWebpackConfig().

const config = await Encore.getWebpackConfig();

// The packages imported by the bundles are installed in the node_modules of the project: look there first, also
// when the bundles are symlinked (Composer "path" repository).
config.resolve.modules = [path.resolve("node_modules"), "node_modules"];

export default config;
