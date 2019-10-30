const respNavigation = $ => {
  let body
  let menu

  const init = () => {
    body = document.querySelector('body')
    menu = document.querySelector('.hamburger')

    applyListeners()
  }

  const applyListeners = () => {
    menu.addEventListener('click', () => toggleClass(body, 'is-active'))
  }

  const toggleClass = (element, stringClass) => {
    if (element.classList.contains(stringClass))
      element.classList.remove(stringClass)
    else element.classList.add(stringClass)
  }

  init()
}
export { respNavigation }
