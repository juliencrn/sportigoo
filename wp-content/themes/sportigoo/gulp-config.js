import { getEnv } from './build-tasks/utils'

const envVar = getEnv()
const env = envVar.dist ? 'dist/' : 'public/'
const pkg = require('./package.json')

module.exports = {
  pkg: {
    name: pkg.name,
  },
  pluginOpts: {
    browserSync: {
      port: 1987,
      server: {
        baseDir: env,
      },
    },
    gSize: {
      showFiles: true,
    },
    pug: {
      pretty: true,
      data: {
        description: pkg.description,
        name: pkg.name,
        version: pkg.version,
      },
    },
    load: {
      rename: {
        'gulp-gh-pages': 'deploy',
        'gulp-cssnano': 'minify',
        'gulp-autoprefixer': 'prefix',
        'gulp-sass': 'sass',
        'gulp-svg-sprite': 'sprite',
        'gulp-imagemin': 'imagemin',
        'imagemin-pngquant': 'imageminPngquant',
        'imagemin-zopfli': 'imageminZopfli',
        'imagemin-mozjpeg': 'imageminMozjpeg',
      },
    },

    prefix: ['last 3 versions', 'Blackberry 10', 'Android 3', 'Android 4', 'last 2 versions', 'firefox >= 4', 'safari 7', 'safari 8', 'IE 8', 'IE 9', 'IE 10', 'IE 11'],
    rename: {
      suffix: '.min',
    },
  },
  paths: {
    base: env,
    sources: {
      docs: 'src/markup/*.pug',
      markup: 'src/markup/**/*.pug',
      overwatch: env + '**/*.{html,js,css}',
      images: 'src/images/**/*.+(png|jpg|gif|svg)',
      svg: 'src/sprite-svg/**/*.svg',
      scripts: {
        root: 'src/script/index.js',
        all: 'src/script/**/*.js',
      },
      styles: {
        root: 'src/style/main.scss',
        all: 'src/style/**/*.scss',
      }
    },
    destinations: {
      css: env + 'css/',
      html: env,
      js: env + 'js/',
      img: env + 'img/',
      svg: env + 'img/',
    },
  },
}
