module.exports = function(grunt) {
  require("load-grunt-tasks")(grunt);
  
  grunt.initConfig({
    "shell": {
      "build-gutenberg": {
        "command": "npx webpack",
        "options": {
          "execOptions": {
            cwd: "gutenberg/blocks/ts"
          }
        }
      }
    }
  });
  
  grunt.registerTask("default", [
    "shell:build-gutenberg"
  ]);
  
};