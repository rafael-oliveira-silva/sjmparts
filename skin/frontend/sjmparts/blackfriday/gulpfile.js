var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');

gulp.task('js_header', function() {
    gulp.src([
        // 'js/prototype/prototype.js',
        '../../../../../../js/jquery/jquery-1.11.1.min.js',
        '../../../../../../js/jquery/jquery-migrate-1.2.1.min.js',
        '../../../../../../js/jquery/jquery_noconflict.js',
        '../../../../../../js/lib/ccard.js',
        '../../../../../../js/prototype/validation.js',
        '../../../../../../js/varien/js.js',
        '../../../../../../js/varien/form.js',
        '../../../../../../js/varien/product.js',
        '../../../../../../js/varien/configurable.js',
        '../../../../../../js/mage/translate.js',
        '../../../../../../js/mage/cookies.js',
        '../../../../../../js/mage/directpost.js',
        '../../../../../../js/mage/captcha.js',
        '../../../../../../js/mage/centinel.js'
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('script_general_header.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('skin/frontend/sjmparts/default/js/'));
});

gulp.task('uglify_only', function() {
    gulp.src(['js/scripts.js', 'js/superfish.js'], { base: './' })
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('./'));
    // .pipe(gulp.dest('skin/frontend/sjmparts/default/js/scripts.min.js'))
});

gulp.task('js_footer', function() {
    gulp.src([
        '../../../../../../js/scriptaculous/builder.js',
        '../../../../../../js/scriptaculous/effects.js',
        '../../../../../../js/scriptaculous/dragdrop.js',
        '../../../../../../js/scriptaculous/controls.js',
        '../../../../../../js/scriptaculous/slider.js',
        'js/bootstrap.js',
        '../../../../../../js/cmsmart/jquery/quickview/jquery.elevateZoom-2.5.5.min.js',
        '../../../../../../js/bannercreator/swiper/dist/js/swiper.jquery.min.js',
        'js/jquery.easing.1.3.js',
        'js/jquery.mobile.customized.min.js',
        'js/jquery.scrollmagic.min.js',
        'js/smoothing-scroll.js',
        'js/jquery.touchSwipe.js',
        'js/jquery.bxslider.min.js',
        'js/jquery.unveil.js',
        'js/cherry-fixed-parallax.js',
        'js/TimelineMax.min.js',
        'js/TweenMax.min.js',
        'js/jquery-ui.js',
        'js/cmsmart/megamenu/cmsmartmenu.js',
        // '../../../../../../js/jquery/jquery_noconflict.js',
        '../../../../../../js/cmsmart/jquery/ajaxcart/cmsmart-ajaxcart.js',
        '../../../../base/default/js/bundle.js',
        '../../../../../../js/calendar/calendar.js',
        '../../../../../../js/calendar/calendar-setup.js',
        '../../../../../../js/my_ibanner/jquery.pikachoose.js'
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('script_general_footer.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('skin/frontend/sjmparts/default/js/'));
});

// @depreceated in favour of Laravel Mix
// gulp.task('css', function() {
//     gulp.src([
//         'js/calendar/calendar-win2k-1.css',
//         'skin/frontend/sjmparts/default/css/font-awesome.css',
//         'skin/frontend/sjmparts/default/css/jquery.bxslider.css',
//         'skin/frontend/sjmparts/default/css/photoswipe.css',
//         'skin/frontend/sjmparts/default/css/bootstrap.css',
//         'skin/frontend/sjmparts/default/css/extra_style.css',
//         'skin/frontend/sjmparts/default/css/responsive.css',
//         'skin/frontend/sjmparts/default/css/superfish.css',
//         'skin/frontend/sjmparts/default/css/camera.css',
//         'skin/frontend/base/default/css/widgets.css',
//         'skin/frontend/sjmparts/default/aw_blog/css/style.css',
//         'skin/frontend/sjmparts/default/cmsmart/quickview/css/quickview.css',
//         'skin/frontend/base/default/my_ibanner/base.css',
//         'skin/frontend/sjmparts/default/css/styles.css',
//         'skin/frontend/sjmparts/default/css/cmsmart/megamenu/megamenu.css',
//         'skin/frontend/sjmparts/default/cmsmart/ajaxcart/css/default.css'
//     ])
//         .pipe(concat('styles_general.min.css'))
//         .pipe(minify())
//         .pipe(gulp.dest('skin/frontend/sjmparts/default/css/'));
// });

//
// gulp.task('css_header', function(){
//     gulp.src([
//         'skin/frontend/sjmparts/default/css/font-awesome.css',
//         // 'skin/frontend/sjmparts/default/css/jquery.bxslider.css',
//         // 'skin/frontend/sjmparts/default/css/photoswipe.css',
//         'skin/frontend/sjmparts/default/css/bootstrap.css',
//         'skin/frontend/sjmparts/default/css/extra_style.css',
//         'skin/frontend/sjmparts/default/css/responsive.css',
//         'skin/frontend/sjmparts/default/css/superfish.css',
//         'skin/frontend/sjmparts/default/css/camera.css',
//         'skin/frontend/base/default/css/widgets.css',
//         // 'skin/frontend/sjmparts/default/aw_blog/css/style.css',
//         'skin/frontend/sjmparts/default/cmsmart/quickview/css/quickview.css',
//         'skin/frontend/base/default/my_ibanner/base.css',
//         'skin/frontend/sjmparts/default/css/styles.css',
//         // 'skin/frontend/sjmparts/default/css/cmsmart/megamenu/megamenu.css',
//         // 'skin/frontend/sjmparts/default/cmsmart/ajaxcart/css/default.css',
//    ])
//    .pipe(concat('styles_general_header.min.css'))
//    .pipe(minify())
//    .pipe(gulp.dest('skin/frontend/sjmparts/default/css/'));
// });
//
// gulp.task('css_footer', function(){
//     gulp.src([
//         'js/calendar/calendar-win2k-1.css',
//         // 'skin/frontend/sjmparts/default/css/font-awesome.css',
//         'skin/frontend/sjmparts/default/css/jquery.bxslider.css',
//         'skin/frontend/sjmparts/default/css/photoswipe.css',
//         // 'skin/frontend/sjmparts/default/css/bootstrap.css',
//         // 'skin/frontend/sjmparts/default/css/extra_style.css',
//         // 'skin/frontend/sjmparts/default/css/responsive.css',
//         // 'skin/frontend/sjmparts/default/css/superfish.css',
//         // 'skin/frontend/sjmparts/default/css/camera.css',
//         // 'skin/frontend/base/default/css/widgets.css',
//         'skin/frontend/sjmparts/default/aw_blog/css/style.css',
//         // 'skin/frontend/sjmparts/default/cmsmart/quickview/css/quickview.css',
//         // 'skin/frontend/base/default/my_ibanner/base.css',
//         // 'skin/frontend/sjmparts/default/css/styles.css',
//         'skin/frontend/sjmparts/default/css/cmsmart/megamenu/megamenu.css',
//         'skin/frontend/sjmparts/default/cmsmart/ajaxcart/css/default.css',
//    ])
//    .pipe(concat('styles_general_footer.min.css'))
//    .pipe(minify())
//    .pipe(gulp.dest('skin/frontend/sjmparts/default/css/'));
// });

// gulp.task('default',['js_header', 'js_footer', 'css', 'css_header', 'css_footer'],function(){});
gulp.task('default', ['js_header', 'js_footer', 'css'], function() {});
