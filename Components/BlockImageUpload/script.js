// document.getElementById('uploadInput')
// const image = document.getElementById('image')
// const overlay = document.getElementById('overlay')
// const downloadBtn = document.getElementById('downloadBtn')

// uploadInput.addEventListener('change', function () {
//   const file = this.files[0]
//   // eslint-disable-next-line no-undef
//   const reader = new FileReader()
//   reader.onload = function (e) {
//     image.src = e.target.result
//   }
//   reader.readAsDataURL(file)
// })

// downloadBtn.addEventListener('click', function () {
//   const size = Math.min(image.width, image.height)
//   const canvas = document.createElement('canvas')
//   canvas.width = size
//   canvas.height = size
//   const context = canvas.getContext('2d')

//   context.drawImage(image, 0, 0, size, size)
//   context.drawImage(overlay, 0, 0, size, size)

//   const link = document.createElement('a')
//   link.download = 'Sharepic.png'
//   link.href = canvas.toDataURL('image/png')
//   link.click()
// })
