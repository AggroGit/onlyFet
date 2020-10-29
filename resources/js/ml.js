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
        nickname:"Nombre de Usuario",
        confSuscriptions: "Configurar suscripciones",
        suscriptions:"Suscripciones",
        uHaveToStripe: "Debes configurar Stripe para poder crear suscripciones",
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
        edit: "Editar",
        existNickName:"Ya existe un usuario con el nickname ",
        country: "País",
        lang: "Idioma",
        description:"Descripción",
        price:"Precio",
        confSusciptions: "Configurar Suscripciones"
      },
      stripe: {
        addVisa:"Antes debes introducir un método de pago",
        create: "Crear Cuenta de Stripe",
        successSuscription: "Te has suscrito correctamente a ",
        sureRemoveSuscriptions: "¿Estás seguro que quieres eliminar tus suscripciones?",
        login: "Login en Stripe",
        days:"días",
        months: "meses",
        problem: "Vaya, parece que hay algún problema con Stripe",
        successSuscriptions: "Sus suscripciones han sido configuradas correctamente",
        info: {

          create: "Registrate en Stripe para recibir dinero",
          login: "Haz login en Stripe para ver tu dinero pendiente de envío"
        },
        confirmChange: "¿Estás seguro que quieres cambiar los precios? Éste proceso puede durar más de lo normal",
        add: "Añadir método de pago",
        cards: "Métodos de pago",
        noCards:"Vaya, parece que no hay tarjetas de crédito",
        sus1:"Suscripción por 30 días",
        sus2:"Suscripción por 3 meses",
        sus3:"Suscripción por 6 meses",
        sus4:"Suscripción por 12 meses",
        conditionsSuscriptions: "*Por cada suscripción la plataforma TPV y la plataforma Onlyfet comisiona un porcentaje para poder continuar operando y mejorando el sitio para ti y tus suscriptores.",
        conditionPrivate: "* Requerirá pagar suscripción",

      },
      post: {
        nowCan: "Ya puedes realizar compras, pagos y suscripciones",
        program: "Programar Publicación",
        post: "Publicación",
        publy: "Publicar",
        error: "Error al publicar",
        date_program: "Fecha de publicación",
        posting: "Subiendo publicación...",
        nopost: "No existe ésta publicación",
        noComments: "Vaya, parece que no hay comentarios",
        comment: "Comentar",
        comments: "Comentarios",
        remove:"Eliminar",
        noposts:"Vaya, parece que no hay más publicaciones"
      }
    })
  ]
})
