const fs = require('fs');
const index_php_text = [
	'<?php',
	'/**',
	' * May the force be with you.',
	' *',
	' * @package ItalyStrap',
	' */',
	'\n',
].join("\n");

// '!README.md', // Questo va sempre copiato, serve per la pagina interna del plugin con la documentazione.
const acm_plugin = [
	'**', // All but exclude the following files...

	/**
	 * Directories
	 */
	'!.git/**',
	'!.sass-cache/**',
	'!node_modules/**',
	'!bower_components/**',
	'!_old/**',
	'!future-inclusions/**',
	'!**/sass/**',
	'!**/docs/**',
	'!**/test*/**',
	'!**/example*/**',
	'!spec/**',
	'!lib/**',

	/**
	 * Files
	 */
	'!.gitattributes',
	'!.gitignore',
	'!bower.json',
	'!Gruntfile.js',
	'!package*.json',
	'!webpack.config.js',
	'!snippets.md',
	'!rector.php',
	'!.wordpress-org/*.svg',
	'!**/*.clover',
	'!**/c3.php',
	'!**/infection.*',
	'!**/*.bat',
	'!**/*.cmd',
	'!**/*.dist',
	'!**/*.lock',
	'!**/*.neon',
	'!**/*.phar',
	'!**/*.yml',
	'!**/*.zip',
	'!**/*.xml',
	'!**/*.map',
	'!**/codecept',
	'!vendor/**/*.json',
	'!vendor/**/CHANGELOG.md',
	'!vendor/**/CONTRIBUTING.md',
	'!vendor/**/README.md',
	'!vendor/**/SECURITY.md',
	'!vendor/**/STABILITY.md',
	'!vendor/**/UPGRADE.md',
];

/**
 * @link https://github.com/fshost/node-dir
 * http://stackoverflow.com/questions/7041638/walking-a-directory-with-node-js
 * https://docs.nodejitsu.com/articles/file-system/how-to-write-files-in-nodejs
 * http://nodeexamples.com/2012/09/28/getting-a-directory-listing-using-the-fs-module-in-node-js/
 * http://www.monitis.com/blog/2011/07/09/6-node-js-recipes-working-with-the-file-system
 *
 */
const traverseFileSystem = function ( currentPath, fileName ) {
	var files = fs.readdirSync( currentPath );
	for ( var i in files ) {
		if ( files[i].search( /vendor|test|node_module/i ) > -1 ) {
			continue;
		}
		// if ( files[i].search( /\.[a-zA-Z]+/ig ) > -1 ) {
		// 	continue;
		// }
		// console.log(files[i]);
		var currentFile = currentPath + '/' + files[i];
		var stats = fs.statSync( currentFile );
		// console.log(stats.isDirectory());
		if ( stats.isDirectory() ) {
			traverseFileSystem( currentFile, fileName );
			fs.writeFile( currentPath + fileName, 'Hello Node.js', function ( err ) {
				if ( err ) {
					console.log( err );
					throw err;
				}
				console.log( 'It\'s saved!' );
				console.log( data );
			});
			// traverseFileSystem( currentFile, fileName );
		}
	}
};

const path = require("path");

module.exports = function(grunt) {
	'use strict';

	/**
	 * https://www.npmjs.com/package/load-grunt-tasks
	 */
	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			// https://github.com/gruntjs/grunt-contrib-uglify 
			// npm install grunt-contrib-uglify --save-dev
			admin: {
				files: {
					'src/Settings/js/italystrap-settings.min.js': [
						'src/Settings/js/src/italystrap-settings.js'
					],
					'admin/js/gallery-settings.min.js': [
						'admin/js/src/gallery-settings.js'
					],
					'src/Widgets/assets/js/widget.min.js': [
						'src/Widgets/assets/js/src/widget.js'
					]
				}
			},
			unveil: {
				files: {
					'js/unveil.min.js': [
						'js/src/unveil.js'
					]
				}
			}
		},

		jshint: { // https://github.com/gruntjs/grunt-contrib-jshint
			all: [
				'js/src/*.js',
			]
		},

		// CSS output mode. Can be: nested, expanded, compact, compressed.
		compass:{ // https://github.com/gruntjs/grunt-contrib-compass
			admin:{
				options: {
					sassDir:['admin/sass'],
					cssDir:['admin/css'],
					imagesDir:['admin/img'],
					httpPath:['../'], // http://stackoverflow.com/questions/13888978/how-to-out-put-images-for-sprite-in-compass-sass
					relativeAssets:true,
					outputStyle: 'compressed'
				}
			},
			widget:{
				options: {
					sassDir:['src/Widgets/assets/sass'],
					cssDir:['src/Widgets/assets/css'],
					imagesDir:['src/Widgets/assets/img'],
					httpPath:['../'], // http://stackoverflow.com/questions/13888978/how-to-out-put-images-for-sprite-in-compass-sass
					relativeAssets:true,
					outputStyle: 'compressed',
					// outputStyle: 'expanded',
					force: true, // Forse rewriting file
				}
			},
			fontello:{
				options: {
					sassDir:['sass'],
					cssDir:['css'],
					fontsDir:['font'],
					imagesDir:['admin/img'],
					httpPath:['../'], // http://stackoverflow.com/questions/13888978/how-to-out-put-images-for-sprite-in-compass-sass
					relativeAssets:true,
					outputStyle: 'compressed'
				}
			},
		},

		fontello: { // https://github.com/jubalm/grunt-fontello
			dist: {
				options: {
					config  : 'font/config.json',
					fonts   : 'font',
					styles  : 'sass',
					scss    : true,
					force   : true,
					// exclude: ['animation.css', 'fontello-ie7-codes.css', 'fontello.eot'],
				}
			}
		},

		csslint: { // http://astainforth.com/blogs/grunt-part-2
			files: ['css/*.css', '!css/bootstrap.min.css',],
			options: {
				"important": false,
				"ids": false,
			}
		},

		bump: { // https://github.com/vojtajina/grunt-bump
			options: {
				files: ['package.json'],
				updateConfigs: [],
				commit: true,
				commitMessage: 'Release v%VERSION%',
				commitFiles: ['package.json'],
				createTag: true,
				tagName: 'v%VERSION%',
				tagMessage: 'Version %VERSION%',
				push: true,
				pushTo: 'upstream',
				gitDescribeOptions: '--tags --always --abbrev=1 --dirty=-d',
				globalReplace: false,
				prereleaseName: false,
				metadata: '',
				regExp: false
			}
		},

		gitcheckout: {
			devtomaster: { // Mi sposto da Dev a master
				options: {
					branch: 'master'
				}
			},
			mastertodev: { // Mi sposto da master a Dev
				options: {
					branch: 'Dev'
				}
			}
		},

		gitmerge: {
			fromdev: { // Prima devo essere in master e poi fare il merge da Dev
				options: {
					branch: 'Dev'
				}
			},
			frommaster: { // Prima devo essere in dev e poi fare il merge sa master
				options: {
					branch: 'master'
				}
			}
		},

		version: {  // https://www.npmjs.com/package/grunt-version
					// http://jayj.dk/using-grunt-automate-theme-releases/
			bower: {
				src: [ 'bower.json' ],
			},
			php: {
				options: {
					prefix: 'Version\\:\\s+'
				},
				src: [ 'italystrap.php' ],
			},
			readme: {
				options: {
					prefix: 'Stable tag\\:\\s'
				},
				src: ['readme.txt']
			},
		},

		wp_readme_to_markdown: { // https://www.npmjs.com/package/grunt-wp-readme-to-markdown
			readme: {
				files: {
					'README.md': 'readme.txt'
				},
			},
		},

		gitcommit: { // https://www.npmjs.com/package/grunt-git
			version: {
				options: {
					message: 'New version: <%= pkg.version %>',
					allowEmpty: true
				},
				files: {
					// Specify the files you want to commit
					// src: [
					// 	'bower.json', //For now bower it is not uploaded
					// 	'readme.txt',
					// 	'README.md',
					// 	'package.json',
					// 	'italystrap.php'
					// 	]
					src: [
						'*.gitignore',
						'*.json', //For now bower it is not uploaded
						'*.txt',
						'*.md',
						'*.php',
						'*.js',
						'functions/default-constants.php'
					]
				}
			},
			first:{
				options: {
					message: 'Commit before deploy of new version'
				},
				files: {
					src: [
						'*.gitignore',
						'*.json',
						'*.txt',
						'*.md',
						'*.php',
						'*.js'
					]
				}
			}
		},

		gitpush: { // https://www.npmjs.com/package/grunt-git
			version: {},
		},

		prompt: { // https://github.com/dylang/grunt-prompt
			target: {
				options: {
					questions: [
						{
						config: 'github-release.options.auth.user', // set the user to whatever is typed for this question
						type: 'input',
						message: 'GitHub username:'
						},
						{
						config: 'github-release.options.auth.password', // set the password to whatever is typed for this question
						type: 'password',
						message: 'GitHub password:'
						}
					]
				}
			}
		},

		exec: { // https://github.com/jharding/grunt-exec
			// https://github.com/a6software/Helpful/blob/master/grunt_automated_testing_config/Gruntfile.js
			composer_update: 'composer update --no-dev && composer dumpautoload -o',
			composer_update_dev: 'composer update && composer dumpautoload',
		},

		compress: { // https://github.com/gruntjs/grunt-contrib-compress
			main: {
				options: {
					archive: '../<%= pkg.name %> <%= pkg.version %>.zip' // Create zip file in theme directory
				},
				files: [
					{
						src: acm_plugin, // What should be included in the zip
						dest: '<%= pkg.name %>/',        // Where the zipfile should go
						filter: 'isFile',
					},
				]
			},
			backup: {
				options: {
					archive: '../<%= pkg.name %> <%= pkg.version %> backup.zip' // Create zip file in theme directory
				},
				files: [
					{
						src: acm_plugin, // What should be included in the zip
						dest: '<%= pkg.name %>/',        // Where the zipfile should go
						// dest: '<%= pkg.name %>/',        // Where the zipfile should go
						filter: 'isFile',
					},
				]
			},
			test: {
				options: {
					archive: '../<%= pkg.name %> <%= pkg.version %> test.zip' // Create zip file in theme directory
				},
				files: [
					{
						src: acm_plugin, // What should be included in the zip
						dest: '<%= pkg.name %>/',        // Where the zipfile should go
						filter: 'isFile',
					},
				]
			},
			temp: {
				options: {
					archive: '../<%= pkg.name %> <%= pkg.version %>.zip' // Create zip file in theme directory
				},
				files: [
					{
						cwd: '../temp/<%= pkg.name %>/',
						src: '**', // What should be included in the zip
						dest: '<%= pkg.name %>/',        // Where the zipfile should go
						filter: 'isFile',
					},
				]
			},
		},

		"github-release": { // https://github.com/dolbyzerr/grunt-github-releaser
			options: {
				repository: 'ItalyStrap/<%= pkg.name %>', // Path to repository
				release: {
					name: '<%= pkg.title %> v.<%= pkg.version %>',
					body: '## New release of <%= pkg.title %> <%= pkg.version %> \nSee the **[changelog](https://github.com/ItalyStrap/<%= pkg.name %>#changelog)**',
				}
			},
			files: {
				src: ['../<%= pkg.name %> <%= pkg.version %>.zip'] // Files that you want to attach to Release
			}

		},

		copy: { // https://github.com/gruntjs/grunt-contrib-copy
			trunk: {
				expand: true,
				src: acm_plugin,
				dest: grunt.option('output-dir') || './.trunk',
				filter: 'isFile',
			},
			tag: {
				expand: true,
				// cwd: 'src',
				src: acm_plugin,
				dest: 'E:/Dropbox/svn-wordpress/<%= pkg.name %>/tags/<%= pkg.version %>/',
				filter: 'isFile',
			},
			temp: {
				expand: true,
				// cwd: 'src',
				src: acm_plugin,
				// src: 'lang/**',
				dest: '../temp/<%= pkg.name %>/',
				filter: 'isFile',
			},
			toTrunk: {
				expand: true,
				cwd: '../temp/<%= pkg.name %>/',
				src: '**',
				// src: 'lang/**',
				dest: 'E:/Dropbox/svn-wordpress/<%= pkg.name %>/trunk/',
				filter: 'isFile',
			},
			toTag: {
				expand: true,
				cwd: '../temp/<%= pkg.name %>/',
				src: '**',
				// src: 'lang/**',
				dest: 'E:/Dropbox/svn-wordpress/<%= pkg.name %>/tags/<%= pkg.version %>/',
				filter: 'isFile',
			},
			dest: {
				expand: true,
				// cwd: 'src',
				src: acm_plugin,
				dest: './dest/',
				filter: 'isFile',
			}
		},

		clean: { // https://github.com/gruntjs/grunt-contrib-clean
			options: {
				force: true,
				// 'no-write': true
			},
			trunk: [
				'E:/Dropbox/svn-wordpress/<%= pkg.name %>/trunk/*'
			],
			temp: [
				'../temp/*'
			],
		},

		sync: { // https://www.npmjs.com/package/grunt-sync
			main: {
				files: [{
					cwd: 'src',
					src: [
						'**', /* Include everything */
						'!**/*.txt' /* but exclude txt files */
						],
					dest: 'bin',
					}],
			pretend: true, // Don't do any IO. Before you run the task with `updateAndDelete` PLEASE MAKE SURE it doesn't remove too much.
			verbose: true // Display log messages when copying files
			}
		},

		"file-creator": {
			"basic": {
				"test/index.php": function(fs, fd, done) {
					console.log(fs.readdirSync('./'));
					// traverseFileSystem( fs, './' );
					// console.log(fs);
					fs.readdir( './', function (err, files) {
						if ( ! err ) {
							// console.log( 'no error ' + files );
							// console.log( path );
							// files.forEach(function(path) {
								// console.log( path );
								// actionOnFile(null, path);
							// })

							files.map(function (file) {
								return path.join('./', file);
							}).filter(function (file) {
								return fs.statSync(file).isDirectory();
							}).forEach(function (file) {
								console.log(index_php_text);
								fs.writeFile(file + '/index.php', index_php_text);
								// fs.writeSync(fd, index_php_text);
								// console.log("%s (%s)", file, path.extname(file));
							});
							fs.writeSync(fd, index_php_text);
						} else {
							console.log( 'error ' + err );
							throw err;
						}
					});
					// console.log(done);
					// fs.writeSync(fd, index_php_text);
					done();
				}
			}
		},

		watch: { // https://github.com/gruntjs/grunt-contrib-watch
			compass: {
				files: ['admin/css/src/sass/*.{scss,sass}'],
				tasks: ['testcompassbuild'],
				options: {
					livereload: 35729,
				},
			},
			js: {
				files: ['admin/js/src/*.js', 'js/src/*.js'],
				tasks: ['testjsbuild'],
				options: {
					livereload: 35729,
				},
			},
			php: {
				files: ['**/*.php'],
				tasks: ['php'],
				options: {
					livereload: 35729,
				},
			},
		}, // End watch

	});

	/**
	 * Controllare gli aggiornamenti
	 * @link https://www.npmjs.com/package/npm-check-updates
	 *
	 * Show any new dependencies for the project in the current directory:
	 * $ npm-check-updates
	 *
	 * Upgrade a project's package.json:
	 * $ npm-check-updates -u
	 *
	 * Upgrade
	 * $ npm install
	 *
	 * Controllare gli aggiornamenti con composer
	 * $ composer update --no-dev
	 * or
	 * $ grunt composer:update
	 *
	 * and
	 *
	 * http://mouf-php.com/optimizing-composer-autoloader-performance
	 * $ composer dump-autoload -o
	 * or
	 * $ grunt composer:dump-autoload -o
	 *
	 */
	
	/**
	 * Tests with codeception and wp-browser
	 * {@link http://www.theaveragedev.com/wp-browser-is-now-compatible-with-codeception-2-3/}
	 * $ vendor/bin/codecept
	 * $ vendor/bin/codecept run
	 * $ vendor/bin/codecept run wpunit
	 * $ vendor/bin/wpcept generate:wpunit wpunit MyTestClass
	 */

	/**
	 * My workflow
	 * Update Readme.txt Documentation
	 * Add new screenshot
	 * Update changelog only in readme.txt
	 *
	 * Aggiornare la lingua con poedit
	 * 
	 * Change version only in package.json
	 *
	 * Open Git Bash and type:
	 * $ grunt preDeploy
	 *
	 * Poi a mano commit su eventuali aggiornamenti, quindi:
	 * 
	 * $ grunt deploy
	 *
	 * E volendo alla fine ricaricare le librerie per i test
	 * $ grunt composer:update
	 *
	 * Poi nella cartella svn-wordpress
	 * dx mouse e +add
	 * dx mouse e commit
	 */
	grunt.registerTask('preDeploy',
		[
		'composer:update:no-dev',
		'composer:dumpautoload -o',
		]
	);
	grunt.registerTask('postDeploy',
		[
		'composer:update',
		'composer:dumpautoload',
		]
	);

	grunt.registerTask(
		'deploy',
		[
			// Commit before deploy of new version
			// 'gitcommit:first', // This will update: '*.json','*.txt','*.md','*.php','*.js'
			// 'gitcheckout:devtomaster',
			// 'gitmerge:fromdev',
			// New version: <%= pkg.version %>
			// 'version', // Change version in package.json
			// 'wp_readme_to_markdown', // Update changelog only in readme.txt
			// 'gitcommit:version',
			// 'prompt',
			// 'gitpush',
			// 'copy:temp',
			// 'compress:temp',
            // 'github-release',
			// 'clean:trunk',
			// 'copy:toTrunk',
			// 'copy:toTag',
			// 'clean:temp',
			'gitcheckout:mastertodev',
			'gitmerge:frommaster',
			'gitpush',
		]
	);

	grunt.registerTask(
		'temp',
		[
			'copy:temp',
			'compress:temp',
			'clean:trunk',
			'copy:toTrunk',
			'copy:toTag',
			'clean:temp',
		]
	);

	grunt.registerTask('release',
		[
			'prompt',
			'compress:main',
			'github-release',
		]
	);

	grunt.registerTask('composer-update',
		[
			'composer:update',
			'composer:dump-autoload -o',
		]
	);


	grunt.registerTask( 'js', [ 'uglify' ] );
	grunt.registerTask( 'js', [ 'uglify' ] );
	grunt.registerTask( 'css', [ 'compass' ] );

	grunt.registerTask( 'int-assets', ['jshint', 'csslint'] );
	grunt.registerTask( 'build-assets', ['js', 'css'] );

	grunt.registerTask( 'php', 'A sample task that logs stuff.', function() {
		return null;
	});

	grunt.registerTask( 'files', 'A sample task that logs stuff.', function() {
		traverseFileSystem( './', '/index.php' );
		return null;
	});


	grunt.registerTask( 'build', [ 'copy:trunk' ] );

	grunt.event.on('watch', function(action, filepath) {
		grunt.log.writeln(filepath + ' has ' + action);
	});

};