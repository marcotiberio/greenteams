import Swiper, { Navigation, A11y, Autoplay, Pagination } from 'swiper'
import 'swiper/css/bundle'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.js'

export default function (sliderBox) {
  const refs = buildRefs(sliderBox)
  const data = getJSON(sliderBox)
  const swiper = initSlider(refs, data)
  const destroyImageWatcher = watchImages(refs.slider, swiper)
  return () => {
    destroyImageWatcher()
    swiper.destroy()
  }
}

// images are lazyloaded, so their height is unknown on init:
// recalculate the slide height whenever one of them arrives
function watchImages (slider, swiper) {
  const updateHeight = () => swiper.updateAutoHeight()
  // `load` does not bubble, so listen in the capture phase
  slider.addEventListener('load', updateHeight, true)
  return () => slider.removeEventListener('load', updateHeight, true)
}

function initSlider (refs, data) {
  const { options } = data
  const config = {
    modules: [Navigation, A11y, Autoplay, Pagination],
    a11y: options.a11y,
    autoHeight: true,
    slidesPerView: 1,
    spaceBetween: 15,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev
    },
    // pagination: {
    //   el: refs.dots,
    //   type: 'bullets',
    //   clickable: true
    // },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 15
      },
      1181: {
        slidesPerView: 4,
        spaceBetween: 30
      }
    }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider, config)
}
