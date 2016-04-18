module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		uglify: {
			// https://github.com/gruntjs/grunt-contrib-uglify 
			// npm install grunt-contrib-uglify --save-dev
			admin: {
				files: {
					'admin/js/admin.min.js': [
						'admin/js/src/admin.js'
						],
					'admin/js/gallery-settings.min.js': [
						'admin/js/src/gallery-settings.js'
						],
					'admin/js/widget.min.js': [
						'admin/js/src/widget.js'
						],
					'js/unveil.min.js': [
						'js/src/unveil.js'
						]
				}
			}
		},

		jshint: { // https://github.com/gruntjs/grunt-contrib-jshint
			all: [
				'js/src/*.js',
				'!js/bootstrap.min.js',
				'!js/jquery.min.js'
			]
		},

		compass:{ // https://github.com/gruntjs/grunt-contrib-compass
			src:{
				options: {
					sassDir:['admin/css/src/scss'],
					cssDir:['admin/css'],
					outputStyle: 'compressed'
				}
			},
		},

		less: { // https://github.com/gruntjs/grunt-contrib-less
			development: {
				options: {
					compress: true,
					yuicompress: true,
					optimization: 2
				},
				files: {
					'admin/css/bootstrap.min.css': [
						'admin/css/src/less/bootstrap.less'
						],
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

		phpcs: { //https://github.com/SaschaGalley/grunt-phpcs
			application: {
				src: [
					// '*.php',
					// 'admin/*.php',
					// 'classes/*.php',
					'classes/class-italystrap-posts-widget.php',
					]
			},
			options: {
				bin: 'C:/Users/fisso/AppData/Roaming/Composer/vendor/bin/phpcs',
				standard: 'WordPress',
				// standard: 'Zend',
				verbose: true,
				// showSniffCodes: true,
			}
		},
		/**
		 * Plugin modificato aggiungendo --no-patch riga 69 file phpcbf.js
		 * execute += ' ' + parameters.join(' ') + ' --no-patch' + ' "' + files.join('" "') + '"';
		 */
		phpcbf: { // https://github.com/mducharme/grunt-phpcbf
			files: {
				src: [
					// '*.php',
					// 'index.php',
					'classes/class-italystrap-posts-widget.php',
					]
			},
			options: {
				bin: 'C:/Users/fisso/AppData/Roaming/Composer/vendor/bin/phpcbf',
				standard: 'WordPress',
				// noPatch: '--no-patch',
				// standard: 'Zend',
				// verbose: true,
				// showSniffCodes: true,
			},
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
			fromdev: { // Prima devo essewre in master e poi fare il merge da Dev
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
					message: 'New version: <%= pkg.version %>'
				},
				files: {
					// Specify the files you want to commit
					src: [
						'bower.json', //For now bower it is not uploaded
						'readme.txt',
						'README.md',
						'package.json',
						'italystrap.php'
						]
				}
			},
			first:{
				options: {
					message: 'Commit before deploy of new version'
				},
				files: {
					src: [
						'*.js',
						'*.txt',
						'*.php',
						'*.json'
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

		compress: { // https://github.com/gruntjs/grunt-contrib-compress
			main: {
				options: {
					archive: '../<%= pkg.name %> <%= pkg.version %>.zip' // Create zip file in theme directory
				},
				files: [
					{
						src: [
							'**' ,
							'!.git/**',
							'!.sass-cache/**',
							'!bower_components/**',
							'!node_modules/**',
							'!.gitattributes',
							'!.gitignore',
							// '!bower.json',
							// '!Gruntfile.js',
							// '!package.json',
							'!*.zip'], // What should be included in the zip
						// dest: '<%= pkg.name %>/',        // Where the zipfile should go
						dest: 'italystrap/',        // Where the zipfile should go
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
						src: [
							'**' ,
							'!.git/**',
							'!.sass-cache/**',
							'!bower_components/**',
							'!node_modules/**',
							'!.gitattributes',
							'!.gitignore',
							// '!bower.json',
							// '!Gruntfile.js',
							// '!package.json',
							'!*.zip'], // What should be included in the zip
						dest: '<%= pkg.name %>/',        // Where the zipfile should go
						// dest: 'italystrap/',        // Where the zipfile should go
						filter: 'isFile',
					},
				]
			}
		},

		"github-release": { // https://github.com/dolbyzerr/grunt-github-releaser
			options: {
				repository: 'overclokk/ItalyStrap-extended', // Path to repository
				release: {
					name: '<%= pkg.name %> <%= pkg.version %>',
					body: '## New release of <%= pkg.name %> <%= pkg.version %> \nSee the **[changelog](https://github.com/overclokk/italystrap-extended#changelog)**',
				}
			},
			files: {
				src: ['../<%= pkg.name %> <%= pkg.version %>.zip'] // Files that you want to attach to Release
			}

		},

		copy: { // https://github.com/gruntjs/grunt-contrib-copy
			tosvn: {
				expand: true,
				// cwd: 'src',
				src: [
					'**',
					'!node_modules/**',
					'!bower_components/**',
					'!tests/**',
					'!bower.json',
					'!composer.json',
					'!Gruntfile.js',
					'!package.json',
					'!README.md',
					'!composer.lock',
					// '!vendor/mobiledetect/**'
					],
				dest: 'E:/Dropbox/svn-wordpress/italystrap/trunk/',
				filter: 'isFile',
			},
			totag: {
				expand: true,
				// cwd: 'src',
				src: [
					'**',
					'!node_modules/**',
					'!bower_components/**',
					'!tests/**',
					'!bower.json',
					'!composer.json',
					'!Gruntfile.js',
					'!package.json',
					'!README.md',
					'!composer.lock',
					// '!vendor/mobiledetect/**'
					],
				dest: 'E:/Dropbox/svn-wordpress/italystrap/tags/<%= pkg.version %>/',
				filter: 'isFile',
			},
			/**
			 * This task is only for copy files in new project site
			 * In "dest" insert the destination of new site that I have to develope
			 * Comment this code if I'm developing in this directory
			 */
			test: {
				expand: true,
				// cwd: 'src',
				src: [
					'**',
					'!node_modules/**',
					'!bower_components/**',
					'!tests/**',
					'!bower.json',
					'!composer.json',
					'!Gruntfile.js',
					'!package.json',
					'!README.md',
					'!composer.lock',
					// 'vendor/mobiledetect/namespaced/Detection/**',
					// 'vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php',
					// '!vendor/mobiledetect/**',
					// '!vendor/webdevstudios/**',
					],
				dest: 'E:/WEB progetti/ItalyStrap/italystrap-extended-<%= pkg.version %>/',
				// dest: 'E:/vagrant-local/www/gjav/htdocs/wp-content/plugins/italystrap/',
				filter: 'isFile',
			},
		},

		clean: { // https://github.com/gruntjs/grunt-contrib-clean
			options: { force: true },
			clean: ['../ItalyStrap']

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

		exec: { // https://github.com/jharding/grunt-exec
				// https://github.com/a6software/Helpful/blob/master/grunt_automated_testing_config/Gruntfile.js
			run_only_unit_tests: {
				cmd: 'php vendor/codeception/codeception/codecept run'
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
			less: {
				files: ['admin/css/src/less/*.less'],
				tasks: ['testlessbuild'],
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

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-phpcs');
	grunt.loadNpmTasks('grunt-phpcbf');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-sync');

	/**
	 * https://www.npmjs.com/package/grunt-composer
	 */
	grunt.loadNpmTasks('grunt-composer');

	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.loadNpmTasks('grunt-bump');

	grunt.loadNpmTasks('grunt-version');
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
	grunt.loadNpmTasks('grunt-git');
	grunt.loadNpmTasks('grunt-prompt');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-github-releaser');
	grunt.loadNpmTasks('grunt-exec');

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
	 * Controllare gli aggiornamenti con composer (mobile detect per ora la copio a mano in lib)
	 * $ composer update
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
	 * Copiare dalla cartella composer dentro lib il file di interesse
	 * Eventualmente copiarlo anche nel tema
	 */
	
	/**
	 * PHPCS commands
	 * phpcs E:/xampp/htdocs/italystrap/wp-content/plugins/italystrap-extended/italystrap.php --standard=WordPress
	 * phpcs E:/xampp/htdocs/italystrap/wp-content/plugins/italystrap-extended/widget/class-widget.php --standard=WordPress
	 * phpcbf E:/xampp/htdocs/italystrap/wp-content/plugins/italystrap-extended/widget/class-widget.php --no-patch --standard=WordPress
	 */
	
	/**
	 * Tests with codeception and wp-browser
	 * $ vendor/bin/wpcept
	 * $ vendor/bin/codecept run
	 * $ vendor/bin/wpcept generate:wpunit wpunit MyTestClass
	 */

	/**
	 * My workflow
	 * Update Readme.txt Documentation
	 * Add new screanshot
	 * Update changelog only in readme.txt
	 * Update the documentation in plugin file
	 * Update Homepage plugin in admin dashboard (the box functionality)
	 *
	 * Aggiornare la lingua con poedit
	 * 
	 * Change version only in package.json
	 *
	 * Open Git Bash and type:
	 * $ grunt deploy
	 * 
	 * Poi nella cartella svn-wordpress
	 * dx mouse e +add
	 * dx mouse e committ
	 */
	grunt.registerTask('deploy', [
								'gitcommit:first',
								'gitcheckout:devtomaster',
								'gitmerge:fromdev',
								'version',
								'wp_readme_to_markdown',
								'gitcommit:version',
								'gitpush',
								'prompt',
								'compress:main',
								'github-release',
								'copy',
								'gitcheckout:mastertodev',
								'gitmerge:frommaster',
								'gitpush',
								]);

	grunt.registerTask('release', [
								'prompt',
								'compress',
								'github-release',
								]);

	grunt.registerTask('dep-update', [
								'composer:update',
								'composer:dump-autoload -o',
								]);

	grunt.registerTask('testcssbuild', ['less', 'compass', 'csslint']);
	grunt.registerTask('testjsbuild', ['jshint', 'uglify']);

	// After botstrap update execute "grunt bootstrap"
	grunt.registerTask('bootstrap', ['uglify:bootstrapJS', 'less']);


	grunt.registerTask('test', ['jshint', 'csslint']);
	grunt.registerTask('build', ['uglify', 'less', 'compass']);

	grunt.registerTask('php', 'A sample task that logs stuff.', function() {
		return null;
	});

	grunt.event.on('watch', function(action, filepath) {
		grunt.log.writeln(filepath + ' has ' + action);
	});

};