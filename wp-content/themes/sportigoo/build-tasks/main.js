import gulp from 'gulp'

import {
  compileScripts,
  lintScripts,
  watchScripts,
} from './scripts'

import {
  compileStyles,
  /*lintStyles,*/
  watchStyles,
} from './styles'

// import {
//   compileMarkup,
//   watchMarkup,
// } from './markup'
//
// import {
//   compileImages,
//   watchImages,
// } from './images'

import {
  compileSpriteSvg,
  watchSpriteSvg,
} from './sprite'

const lint = gulp.parallel(lintScripts)
lint.description = 'lint all source'

const compile = gulp.parallel(
  // compileMarkup,
  compileStyles,
  compileScripts,
  // compileImages,
  compileSpriteSvg
);
compile.description = 'compile all source'

const watch = gulp.parallel(
  // watchMarkup,
  watchStyles,
  watchScripts,
  // watchImages,
  watchSpriteSvg
);
watch.description = 'watch for changes to all source'

export {
  compile,
  lint,
  watch,
}