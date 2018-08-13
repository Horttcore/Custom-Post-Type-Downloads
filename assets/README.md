# gulp-tasks
This repository contains a lightweight easy to extend gulp task, meant to kick off basic web projects as fast as possible.

## Scripts
- `npm run build` Compile and minify the SCSS/SASS and JS files
- `npm run styles` Compiles the SCSS files
- `npm run scripts` Compiles the JS files
- `npm run dev` Default watch task. Uses the styles and scripts tasks.
- `npm run sync` Watch task with browsersync. Uses styles and scripts tasks.
- `npm run guide` Generates a styleguide using `mdcss`

## Config
Main configuration happens in the `gulpfile.js`-File. It contains two objects for handling input and output files.

### Object `paths`
This Object configs where files are located and where to compile them to.
```js
var paths = {
	'dirs'	: {
// source folder
		'from' 	: './src',
// output folder
		'to'	: './dist'
	},
	'scss'	: {
		'files' : [
// Add scss files which will be compiled individually
			'./src/scss/app.scss'
		],
		'includePaths' : [
// Add as many include paths, as you wish
		]
	},
	'js'	: {
		'files' : [
      // Add js files which will be concatenated in the same order as this array
			'./src/js/app.js'
		]
	},
};
```

### Placeholders
In this project are two placeholder, one Downloads for the name of the custom post type and the other downloads for the slug of the custom post. Make sure to rename this probably. Also make sure to change the filenames.

Note: a script to this by command is currently still in development

### Todos
Search the project for appearances of @todo. This will point you to the places where you can change the output in the editor and what is save in the database.