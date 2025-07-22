// IMPORTANT: Replace with your project's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyA-CG_016nfMG3_Vxks6YdWN-WoU1-r_Z0",
  authDomain: "glovestradelite.firebaseapp.com",
  projectId: "glovestradelite",
  storageBucket: "glovestradelite.firebasestorage.app",
  messagingSenderId: "621003352768",
  appId: "1:621003352768:web:f9c8e9d55daa6937801b25"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();
const db = firebase.firestore();
const storage = firebase.storage();