import gulp from'gulp'
import gConfig from '../gulp-config'
import { getEnv } from './utils'
import imageminPngquant from 'imagemin-pngquant'
import imageminZopfli from 'imagemin-zopfli'
import imageminMozjpeg from 'imagemin-mozjpeg'
import imageminGiflossy from 'imagemin-giflossy'
import pluginLoader from 'gulp-load-plugins'

const opts = gConfig.pluginOpts
const env = getEnv()
const src = gConfig.paths.sources
const dest = gConfig.paths.destinations
const plugins= pluginLoader(opts.load)

const compileImages = () => (
    gulp
        .src(src.images)
        .pipe(plugins.plumber())
        .pipe(plugins.imagemin([
            //png
            imageminPngquant({
                speed: 1,
                quality: 95 //lossy settings
            }),
            imageminZopfli({
                more: true
            }),
            //gif very light lossy, use only one of gifsicle or Giflossy
            imageminGiflossy({
                optimizationLevel: 3,
                optimize: 3, //keep-empty: Preserve empty transparent frames
                lossy: 2
            }),
            //jpg very light lossy, use vs jpegtran
            imageminMozjpeg({                quality: 90
            }),
        ]))
        .pipe(env.deploy ? noop() : gulp.dest(dest.img))
)
compileImages.description = `Optimized images source(${src.images})`

const watchImages = () =>
  gulp.watch(src.images, gulp.series(compileImages))
watchImages.description = `watch for images  source(${src.images})`

export {
  compileImages,
  watchImages,
}
