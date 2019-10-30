import gulp from 'gulp'
import { getEnv } from './build-tasks/utils'

import {compileScripts,lintScripts,watchScripts,} from './build-tasks/scripts'
import { compileStyles, lintStyles, watchStyles } from './build-tasks/styles'
import { compileMarkup, watchMarkup } from './build-tasks/markup'
import { compileImages, watchImages } from './build-tasks/images'
import { compileSpriteSvg, watchSpriteSvg } from './build-tasks/sprite'

import { compile, lint, watch as watchEverything } from './build-tasks/main'
import { deploy } from './build-tasks/deploy'
import { stat } from './build-tasks/stat'
import { serve as serveEverything } from './build-tasks/server'

export {
  deploy,
  serveEverything as serve,
  stat,
  compile,
  lint,
  watchEverything as watch,
  compileScripts,
  compileMarkup,
  compileStyles,
  compileImages,
  compileSpriteSvg,
  lintScripts,
  lintStyles,
  watchScripts,
  watchStyles,
  watchMarkup,
  watchImages,
  watchSpriteSvg,
}
const env = getEnv()
// NOTE:: wrapper functions necessary to get metadata for tasks as intended
const serve = (cb) => serveEverything(cb)
const watch = (cb) => watchEverything(cb)
const defaultTasks = env.deploy
  ? gulp.series(deploy)
  : gulp.parallel(
      serve,
      watch,
    )

export default defaultTasks
