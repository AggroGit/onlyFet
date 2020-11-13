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

    // espanol
      new MLanguage('es').create({
        title: 'Hola {0}!',
        chat: {
          entrada: "Escribe tu mensaje",
          nochat: "Vaya, parece que no tienes chats",
          lookUser: "Buscar chat",
          block: "Bloquear",
          unBlock: "Desbloquear",
          report: "Reportar",
          confBlock: "¿Estas seguro que quieres bloquar a ",
          confReport: "¿Estas seguro que reportar a ",
          notAvailable: "Este chat ya no está disponible"
        },
        auth: {
          socialGoogle: "Log In con Google",
          socialFacebook: "Log In con Google",
          recoverEmail : "Te hemos enviado un correo de recuperación",
          forget: "¿Has perdido la contraseña?",
          recu: "Recuperar contraseña",
          legalTerms:"He leído y acepto los términos legales",
          nickname:"Nombre de Usuario",
          influencerQ: "¿Te consideras influencer?",
          confSuscriptions: "Configurar suscripciones",
          suscriptions:"Suscripciones",
          suscriptionsAndNotis: "Suscripciones y Notificaciones",
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
          editProfile: "Editar Perfil",
          existNickName:"Ya existe un usuario con el nickname ",
          country: "País",
          lang: "Idioma",
          description:"Descripción",
          price:"Precio",
          noSus:"Aún no te has suscrito a ningún influencer",
          confSusciptions: "Configurar Suscripciones"
        },
        stripe: {
          suscribed: "Suscrito",
          sureUnsuscribe: "¿Estás seguro que quieres eliminar tus suscripciones? Ten en cuenta que no podrás acceder de nuevo a este perfil y tu suscripción no será reembolsada.",
          ev:"cada",
          addVisa:"Antes debes introducir un método de pago",
          create: "Crear Cuenta de Stripe",
          successSuscription: "Te has suscrito correctamente a ",
          sureRemoveSuscriptions: "¿Estás seguro que quieres eliminar tus suscripciones? Ten en cuenta que no podrás acceder de nuevo a este perfil y tu suscripción no será reembolsada.",
          login: "Login en Stripe",
          days:"días",
          months: "meses",
          onlyPremium: "Debes suscribirte para ver el contenido",
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
        },
        main: {
          search: "🔍   Búsqueda de usuario",
          popu: "Más populares",
          news: "Nuevos usuarios",
          susc: "Mis suscripciones",
          title: "Perfiles Recomendados",
          nop: "Vaya, parece que no hay usuarios que mostrar"

        },
        menu : {
          home:"Inicio",
          news:"Novedades",
          publi: "Publicar",
          profile: "Mi perfil",
          chats:"Chats",
          login: "Login",
          register: "Register",
          logout: "Desconectar",
          faqs: "FAQs"
        },
        propina: {
          label: "Cantidad de Propina",
          send: "Enviar",
          mensaje: "Mensaje",
          cantRecive: "Parece que el usuario no puede aceptar propinas"
        }
      })
  ]
})
