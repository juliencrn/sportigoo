import gulp from'gulp'
import gConfig from '../gulp-config'
import { getEnv } from './utils'
import pluginLoader from 'gulp-load-plugins'

const opts = gConfig.pluginOpts
const env = getEnv()
const src = gConfig.paths.sources
const dest = gConfig.paths.destinations
const plugins= pluginLoader(opts.load)

const compileSpriteSvg = () =>
    gulp
        .src(src.svg)
        .pipe(plugins.plumber())
        .pipe(plugins.sprite({
            mode: {
                symbol: {
                    sprite: 'sprite.svg'
                }
            }
        }))
        .pipe(env.deploy ? noop() : gulp.dest(dest.svg))
compileSpriteSvg.description = `create sprite svg from source(${src.svg})`
compileSpriteSvg.flags = {
    '--deploy': `only create minified output in the deployment directory ${dest.svg}`,
    '--dist': `output to dist directory`,
}

const watchSpriteSvg = () =>
    gulp.watch(src.svg, gulp.series(compileSpriteSvg))
watchSpriteSvg.description = `watch for SVG to sprite source(${src.svg})`

export {
  compileSpriteSvg,
  watchSpriteSvg,
}
