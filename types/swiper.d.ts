// Swiper v9 doesn't properly expose types via package.json "exports".
// This shim re-exports the bundled type declarations.
declare module 'swiper' {
  import Swiper from 'swiper/types/swiper-class'
  export default Swiper
  export type { SwiperOptions } from 'swiper/types/swiper-options'
  export { Navigation, Pagination, Autoplay, A11y } from 'swiper/types/modules'
}

declare module 'swiper/css/bundle' {}
