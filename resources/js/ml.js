import Vue from 'vue'
import { MLInstaller, MLCreate, MLanguage } from 'vue-multilanguage'

Vue.use(MLInstaller)

export default new MLCreate({
  initial: 'es',
  save: process.env.NODE_ENV === 'production',
  languages: [
    // INGLES
    new MLanguage('english').create({
      title: 'Hello {0}!',
      msg: 'You have {f} friends and {l} likes'
    }),

    // CATALÁN
    new MLanguage('es').create({
      title: 'Hola {0}!',
      msg: 'Tienes {f} amigos y {l} me gustas',
      chat: {
        entrada: "Escribe tu mensaje",
        nochat: "Vaya, parece que no tienes chats",
        lookUser: "Buscar chat"
      },
      auth: {
        prError: "Vaya",
        error: "Parece que el usuario no existe o tienes mal los datos",
        exist: "Parece que el usuario ya existe",
        login: "Iniciar Sesión",
        register: "Registro",
        changelogin: "Cambiar a login",
        changeRgister: "Cambiar a registro",
        change: "Guardar Cambios",
        email: "Email",
        phone: "Teléfono",
        password: "Contraseña",
        RepPassword: "Repetir Contraseña",
        name: "Nombre",
        newPass: "Nueva Contraseña",
        edit: "Editar"
      },
      stripe: {
        create: "Crear Cuenta de Stripe",
        login: "Login en Stripe",
        info: {
          create: "Registrate en Stripe para recibir dinero",
          login: "Haz login en Stripe para ver tu dinero pendiente de envío"
        },
        add: "Añadir método de pago",
        cards: "Métodos de pago",
        noCards:"Vaya, parece que no hay tarjetas de crédito"
      },
      post: {
        program: "Programar Publicación",
        post: "Publicación",
        publy: "Publicar",
        error: "Error al publicar",
        date_program: "Fecha de publicación"
      }
    })
  ]
})
