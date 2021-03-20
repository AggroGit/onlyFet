import Vue from 'vue'
import { MLInstaller, MLCreate, MLanguage } from 'vue-multilanguage'

Vue.use(MLInstaller)

export default new MLCreate({
  initial: 'en',
  save: process.env.NODE_ENV === 'production',
  languages: [
    // INGLES
    new MLanguage('en').create({
   "title": "Welcome!",
   "chat": {
      "entrada": "Write your message",
      "nochat": "It seems you have no chats",
      "lookUser": "Search chat",
      "block": "Block",
      "unBlock": "Unblock",
      "report": "Report",
      "confBlock": "Are you sure you wish to block this user?",
      "confReport": "Are you sure you wish to report this user?",
      "notAvailable": "This chat is no longer available"
   },
   "auth": {
     "startWiningMoney": "Start to earn money",
     "becameInfluencer":"Became Influencer User",
     "documents": "Documents",
     "normalUser": "Normal User",
     "influencerUser":"Influencer User",
     "requisistsInfluencer": "Se requieren de 3 documentos de verificación:\n-Documento de identidad o acreditativo, parte frontal y trasero\n-Selfie con documento en la mano y que se ve bien el documento",
      "socialFacebook" : "Login with Facebook",
      "socialGoogle" : "Login with Google",
      "socialTwitter" : "Login with Twitter",
      "registerFacebook": "Register with Facebook",
      "registerTwitter": "Register with Twitter",
      "registerGoogle": "Register with Google",
      "recoverEmail": "We´ve sent you a recovery email",
      "forget": "Did you forget your password?",
      "recu": "Recover password",
      "legalTerms": "I have read and accepted the T&Cs",
      "nickname": "User name",
      "influencerQ": "Do you consider yourself an Influencer?",
      "confSuscriptions": "Manage subscriptions",
      "suscriptions": "Subscriptions",
      "suscriptionsAndNotis": "Notifications",
      "uHaveToStripe": "You must set up Stripe to start your subscriptions ",
      "prError": "Oops",
      "error": "It seems that this user does not exist or you may have entered it incorrectly ",
      "exist": "This user already exists",
      "login": "Start session",
      "register": "Register",
      "changelogin": "Switch to login",
      "changeRgister": "Switch to register",
      "change": "Save changes",
      "email": "Email",
      "phone": "Phone number",
      "password": "Password",
      "RepPassword": "Repeat password",
      "name": "Name",
      "newPass": "New password",
      "edit": "Edit",
      "editProfile": "Edit profile",
      "existNickName": "This nickname has already been taken",
      "country": "Country",
      "lang": "Language",
      "description": "Description",
      "price": "Price",
      "noSus": "You are not subscribed to any Influencer profile yet",
      "confSusciptions": "Manage subscriptions",
      "adulttext": "I accept that I am of legal age",
      "bank_account": "Bank account",
      "birthday": "Birthday date",
      "promotionalCode": "Promotional code",

   },
   "stripe": {
      "suscribed": "Subscribed",
      "sureUnsuscribe": "Are you sure you wish to cancel your subscriptions? Beware that you won´t be able to access this profile again and your subscription won´t be reimbursed",
      "ev": "each",
      "addVisa": "You must enter a payment method beforehand",
      "create": "Create Stripe account",
      "successSuscription": "You have successfully subscribed to ",
      "sureRemoveSuscriptions": "Are you sure you wish to cancel your subscriptions? Beware that you won´t be able to access this profile and your subscription won´t be reimbursed ",
      "login": "Log in to Stripe",
      "days": "days",
      "months": "months",
      "onlyPremium": "You must be subscribed to watch this content",
      "problem": "Oops, it seems there´s something wrong with Stripe ",
      "successSuscriptions": "Your subscriptions have been successfully set up",
      "info": {
         "create": "Register to Stripe to start earning cash",
         "login": "Log in to Stripe to see your pending money "
      },
      "confirmChange": "Are you sure you wish to change your prices? This process can take longer than usual ",
      "add": "Add payment method",
      "cards": "Payment methods",
      "noCards": "It seems there are no credit cards",
      "sus1": "30 day subscription",
      "sus2": "3 months subscription",
      "sus3": "6 months subscription",
      "sus4": "12 months subscription",
      "conditionsSuscriptions": "*For each subscription the TPV and OnlyFet platforms charge a commission to continue to operate and improve the site for you and your subscribers.",
      "conditionPrivate": "* Requires to pay for a subscription"
   },
   "post": {
      "nowCan": "You can now make purchases, payments and subscriptions ",
      "program": "Schedule post",
      "post": "Post",
      "publy": "Post",
      "error": "Error when trying to post",
      "date_program": "Post date",
      "posting": "Updating post...",
      "nopost": "This post does not exist",
      "noComments": "There are no comments",
      "comment": "Leave a comment",
      "comments": "Comments",
      "remove": "Delete",
      "noposts": "There are no more posts"
   },
   "main": {
      "search": "🔍   User search",
      "popu": "Most popular",
      "news": "New users",
      "susc": "My subscriptions",
      "title": "Recommended profiles",
      "nop": "There are no more users to show"
   },
   "menu": {
      "home": "Start",
      "news": "News",
      "publi": "Publish",
      "profile": "My profile",
      "chats": "Chats",
      "login": "Log in",
      "register": "Register",
      "logout": "Log out",
      "faqs": "FAQs"
   },
   "propina": {
      "label": "Tip amount",
      "send": "Send",
      "mensaje": "Message",
      "cantRecive": "It seems this user cannot accept tips"
   },
   "auction": {
     "createTitle": "Crear una subasta",
     "media": "Imágenes para la subasta",
     "title" : "Título",
     "description" : "Descripción",
     "finish_at": "Fecha y hora de finalización de la puja",
     "choosedate": "Antes debes seleccionar una fecha y hora de finalización de subasta",
     "initial_price": "Precio inicial",
     "description": "Descripción",
     "createAuction": "Crear Subasta",
     "onlyOne": "Ya tienes una subasta en marcha, debes esperar a que finalize para publicar otra",
     "maxCurrent":"Puja máxima actual",
     "auctionInfo": "*Un porcentaje de la cantidad recaudada irá destinada como donación a una asociación en la lucha contra el cáncer de mama.",
     "cuntBideUp": "Canidad para subir la puja ",
     "bideUpMore": "Subir la puja",
     "notUser": "No puedes pujar por tu propio producto",
     "finishedAuction": "La Subasta ha finalizado",
     "congratsYouWinAuction": "¡Enhorabuena, has ganado la puja!",
     "max":"Puja máxima",
     "detailSend": "Te enviaré tan pronto como sea posible el artículo",
     "enterCurrentAuction": "¡ ENTRA A LA PUJA ACTIVA !",
     "onlyInfluencer": "Tienes que ser influencer para poder crear subastas",
     "currentAuctingUser": "Tiene una puja activa"
   },
   "shop": {
     "search": "🔍  Buscar Producto",
     "buy": "Comprar",
     "price": "Precio:",
     "noProducts": "No se han encontrado productos",
     "filter": "Filtrar",
     "addCart": "Añadir al carrito",
     "noProducts": "No se han encontrado productos",
     "expensiveFirst": "Más caros primeros",
     "cheapestFirst": "Más baratos primeros",
     "haveLoggin": "Tienes que iniciar sesión para poder comprar",
     "haveAddDirection": "Tienes que añadir una dirección de envío",
     "cart": "Carrito",
     "direction":"Dirección de envío",
     "totalPriceOrders": "Precio subtotal",
     "sendingCost": "Precio de envío",
     "totalPrice": "Precio total",
     "problemwithyourVisa" : "Parece que hay un problema con tu tarjeta de credito",
     "noMinPrice": "No llegas al precio mínimo de compra (5€)",
     "ordered_at": "Pedido el",
     "needHelp": "¿Necesitas ayuda?",
     "correoAyudaShop": "shop@onlyfet.com",
     "history": "Historial",
     "shop": "Tienda",
     "sureRemoving": "¿Estás seguro que quieres eliminar el producto de la cesta?"
   },
   "verification": {
     "pending": "Tu perfil está pendiente de verificación. En breve tendrás una respuesta de la plataforma",
     "pricePending": "Debes introducir los precios de tu suscripción",
     "postPending": "Para empezar a ganar dinero y ser usuario influencer pedimos un mínimo de 10 publicaciones. Actualmente ",
     "havetoBeVerified": "Tu perfil debe ser validado antes"
   }
}),

    // espanol
      new MLanguage('es').create({
   "title": "¡Hola!",
   "chat": {
      "entrada": "Escribe tu mensaje",
      "nochat": "Vaya, parece que no tienes chats",
      "lookUser": "Buscar chat",
      "block": "Bloquear",
      "unBlock": "Desbloquear",
      "report": "Reportar",
      "confBlock": "¿Estás seguro que quieres bloquear a",
      "confReport": "¿Estás seguro que quieres reportar a",
      "notAvailable": "Este chat ya no está disponible"
   },
   "auth": {
     "startWiningMoney": "Comienza a ganar dinero",
     "becameInfluencer":"Ser Usuario Influencer",
     "documents": "Documentos",
     "normalUser": "Usuario normal",
     "influencerUser":"Usuario Influencer",
     "requisistsInfluencer": "Se requieren de 3 documentos de verificación:\n-Documento de identidad o acreditativo, parte frontal y trasero\n-Selfie con documento en la mano y que se ve bien el documento",
      "socialFacebook" : "Login with Facebook",
      "socialGoogle" : "Login with Google",
      "socialTwitter" : "Login with Twitter",
      "registerTwitter": "Registro con Twitter",
      "registerFacebook": "Registro con Facebook",
      "registerGoogle": "Registro con Google",
      "recoverEmail": "Te hemos enviado un correo de recuperación",
      "forget": "¿Has perdido la contraseña?",
      "recu": "Recuperar contraseña",
      "legalTerms": "He leído y acepto los términos legales",
      "nickname": "Nombre de usuario",
      "influencerQ": "¿Te consideras Influencer?",
      "confSuscriptions": "Configurar suscripciones",
      "suscriptions": "Suscripciones",
      "suscriptionsAndNotis": "Suscripciones y notificaciones",
      "uHaveToStripe": "Debes configurar Stripe para poder crear suscripciones",
      "prError": "Vaya",
      "error": "Parece que el usuario no existe o tienes mal los datos",
      "exist": "Parece que el usuario ya existe",
      "login": "Iniciar sesión",
      "register": "Registro",
      "changelogin": "Cambiar a login",
      "changeRgister": "Cambiar a registro",
      "change": "Guardar cambios",
      "email": "Email",
      "phone": "Teléfono",
      "password": "Contraseña",
      "RepPassword": "Repetir contraseña",
      "name": "Nombre",
      "newPass": "Nueva contraseña",
      "edit": "Editar",
      "editProfile": "Editar perfil",
      "existNickName": "Ya existe un usuario con este nickname",
      "country": "País",
      "lang": "Idioma",
      "description": "Descripción",
      "price": "Precio",
      "noSus": "Aún no te has suscrito a ningún Influencer",
      "confSusciptions": "Configurar suscripciones",
      "adulttext": "Acepto que soy mayor de edad",
      "bank_account": "Cuenta bancaria",
      "birthday": "Fecha de nacimiento",
      "promotionalCode": "Código promocional",
   },
   "stripe": {
      "suscribed": "Suscrito",
      "sureUnsuscribe": "¿Estás seguro que quieres eliminar tus suscripciones? Ten en cuenta que no podrás acceder de nuevo a este perfil y tu suscripción no será reembolsada.",
      "ev": "cada",
      "addVisa": "Antes debes introducir un método de pago",
      "create": "Crear cuenta de Stripe",
      "successSuscription": "Te has suscrito correctamente a ",
      "sureRemoveSuscriptions": "¿Estás seguro que quieres eliminar tus suscripciones? Ten en cuenta que no podrás acceder de nuevo a este perfil y tu suscripción no será reembolsada.",
      "login": "Login en Stripe",
      "days": "días",
      "months": "meses",
      "onlyPremium": "Debes suscribirte para ver el contenido",
      "problem": "Vaya, parece que hay algún problema con Stripe",
      "successSuscriptions": "Tus suscripciones han sido configuradas correctamente ",
      "info": {
         "create": "Regístrate en Stripe para recibir dinero",
         "login": "Haz login en Stripe para ver tu dinero pendiente de envío"
      },
      "confirmChange": "¿Estás seguro que quieres cambiar los precios? Este proceso puede durar más de lo normal",
      "add": "Añadir método de pago",
      "cards": "Métodos de pago",
      "noCards": "Vaya, parece que no hay tarjetas de crédito",
      "sus1": "Suscripción por 30 días",
      "sus2": "Suscripción por 3 meses",
      "sus3": "Suscripción por 6 meses",
      "sus4": "Suscripción por 12 meses",
      "conditionsSuscriptions": "*Por cada suscripción la plataforma TPV y la plataforma OnlyFet comisiona un porcentaje para poder continuar operando y mejorando el sitio para ti y tus suscriptores.",
      "conditionPrivate": "* Requerirá pagar suscripción"
   },
   "post": {
      "nowCan": "Ya puedes realizar compras, pagos y suscripciones",
      "program": "Programar publicación",
      "post": "Publicación",
      "publy": "Publicar",
      "error": "Error al publicar",
      "date_program": "Fecha de publicación",
      "posting": "Subiendo publicación...",
      "nopost": "No existe esta publicación",
      "noComments": "Vaya, parece que no hay comentarios",
      "comment": "Comentar",
      "comments": "Comentarios",
      "remove": "Eliminar",
      "noposts": "Vaya, parece que no hay más publicaciones"
   },
   "main": {
      "search": "🔍   Búsqueda de usuario",
      "popu": "Más populares",
      "news": "Nuevos usuarios",
      "susc": "Mis suscripciones",
      "title": "Perfiles recomendados",
      "nop": "Vaya, parece que no hay usuarios que mostrar"
   },
   "menu": {
      "home": "Inicio",
      "news": "Novedades",
      "publi": "Publicar",
      "profile": "Mi perfil",
      "chats": "Chats",
      "login": "Login",
      "register": "Registrarse",
      "logout": "Desconectar",
      "faqs": "FAQs"
   },
   "propina": {
      "label": "Cantidad de propina",
      "send": "Enviar",
      "mensaje": "Mensaje",
      "cantRecive": "Parece que el usuario no puede aceptar propinas"
   },
   "auction": {
     "createTitle": "Crear una subasta",
     "media": "Imágenes para la subasta",
     "title" : "Título",
     "description" : "Descripción",
     "finish_at": "Fecha y hora de finalización de la puja",
     "choosedate": "Antes debes seleccionar una fecha y hora de finalización de subasta",
     "initial_price": "Precio inicial",
     "description": "Descripción",
     "createAuction": "Crear Subasta",
     "onlyOne": "Ya tienes una subasta en marcha, debes esperar a que finalize para publicar otra",
     "maxCurrent":"Puja máxima actual",
     "auctionInfo": "*Un porcentaje de la cantidad recaudada irá destinada como donación a una asociación en la lucha contra el cáncer de mama.",
     "cuntBideUp": "Canidad para subir la puja ",
     "bideUpMore": "Subir la puja",
     "notUser": "No puedes pujar por tu propio producto",
     "finishedAuction": "La Subasta ha finalizado",
     "congratsYouWinAuction": "¡Enhorabuena, has ganado la puja!",
     "max":"Puja máxima",
     "detailSend": "Te enviaré tan pronto como sea posible el artículo",
     "enterCurrentAuction": "¡ ENTRA A LA PUJA ACTIVA !",
     "onlyInfluencer": "Tienes que ser influencer para poder crear subastas",
     "currentAuctingUser": "Hay una puja activa"
   },
   "shop": {

     "search": "🔍  Buscar Producto",
     "buy": "Comprar",
     "price": "Precio:",
     "noProducts": "No se han encontrado productos",
     "filter": "Filtrar",
     "addCart": "Añadir al carrito",
     "noProducts": "No se han encontrado productos",
     "expensiveFirst": "Más caros primeros",
     "cheapestFirst": "Más baratos primeros",
     "haveLoggin": "Tienes que iniciar sesión para poder comprar",
     "haveAddDirection": "Tienes que añadir una dirección de envío",
     "cart": "Carrito",
     "direction":"Dirección de envío",
     "totalPriceOrders": "Precio subtotal",
     "sendingCost": "Precio de envío",
     "totalPrice": "Precio total",
     "problemwithyourVisa" : "Parece que hay un problema con tu tarjeta de credito",
     "noMinPrice": "No llegas al precio mínimo de compra (5€)",
     "ordered_at": "Pedido el",
     "needHelp": "¿Necesitas ayuda?",
     "correoAyudaShop": "shop@onlyfet.com",
     "history": "Historial",
     "shop": "Tienda",
     "sureRemoving": "¿Estás seguro que quieres eliminar el producto de la cesta?"

   },
   "verification": {
     "pending": "Tu perfil está pendiente de verificación. En breve tendrás una respuesta de la plataforma",
     "pricePending": "Debes introducir los precios de tu suscripción",
     "postPending": "Para empezar a ganar dinero y ser usuario influencer pedimos un mínimo de 10 publicaciones. Actualmente ",
     "havetoBeVerified": "Tu perfil debe ser validado antes"
   }
})
  ]
})
