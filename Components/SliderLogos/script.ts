import Swiper, { Navigation, A11y, Autoplay, Pagination } from 'swiper'
import type { SwiperOptions } from 'swiper'
import 'swiper/css/bundle'
import { buildRefs, getJSON } from '@/assets/scripts/helpers.ts'

interface SliderLogosData {
  options: {
    a11y: SwiperOptions['a11y']
    autoplay: boolean
    autoplaySpeed: number
  }
}

export default function (el: HTMLElement) {
  const refs = buildRefs(el)
  const data = getJSON(el) as unknown as SliderLogosData
  const swiper = initSlider(refs, data)
  return () => swiper.destroy()
}

function initSlider (
  refs: Record<string, Element | null>,
  data: SliderLogosData
): Swiper {
  const { options } = data
  const config: SwiperOptions = {
    modules: [Navigation, A11y, Autoplay, Pagination],
    a11y: options.a11y,
    slidesPerView: 2,
    spaceBetween: 20,
    navigation: {
      nextEl: refs.next as HTMLElement,
      prevEl: refs.prev as HTMLElement
    },
    pagination: {
      el: refs.dots as HTMLElement,
      type: 'bullets',
      clickable: true
    },
    breakpoints: {
      640: {
        slidesPerView: 3,
        spaceBetween: 20
      },
      1181: {
        slidesPerView: 4,
        spaceBetween: 20
      }
    }
  }
  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed
    }
  }

  return new Swiper(refs.slider as HTMLElement, config)
}
