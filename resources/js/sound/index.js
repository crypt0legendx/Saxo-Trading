import store from '../store'

export const Sound =  {
  install(Vue) {
    Vue.prototype.$sound = {
      play(sound) {
        try {
          if(store.state.user.sound == 'Disabled') return
          var audio = new Audio('/assets/' + sound)
          audio.crossOrigin = 'anonymous';
          var promise = audio.play()

          if(promise !== undefined) {
            promise.then(function() {
              //
            }).catch(function(error) {
              console.log(error)
            })
          }
        } catch(e) {
          console.log("Cannot play audio")
        }
      }
    }
  }
}
