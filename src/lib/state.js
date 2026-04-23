// ============= GLOBAL STATE & MOCK DATA =============

// optional only; admin now uses backend
export const MOCK_USERS = [];

// mock student login only
export const registeredStudents = [
  { studentNumber: '24-22-241', password: 'student123', name: 'Stephanie', program: 'BS Information Technology', role: 'student' },
];

export const state = {
  currentUser: null,
  conversations: [],
  activeId: null,
  isSignUp: false,
  showPassword: false,
  sidebarCollapsed: false,
  currentPage: 'chat',

  // now loaded from backend
  announcements: [],
  events: [],
  chatLogs: [],

  // admin panel already connected, chatbot can still use this for now
  knowledgeBase: [
    { id: 'kb1', question: 'How do I enroll?', answer: "Visit the Registrar's Office or log in to the student portal." },
    { id: 'kb2', question: 'What are the admission requirements?', answer: 'Form 138, Certificate of Good Moral Character, PSA Birth Certificate, 2x2 ID photos, and application form.' },
    { id: 'kb3', question: 'What courses does UDM offer?', answer: 'Business, Education, Engineering, IT, Nursing, and more.' },
    { id: 'kb4', question: 'Can I wear civilian clothes?', answer: 'Only on designated wash days or special events.' },
    { id: 'kb5', question: 'How to compute GWA?', answer: 'Multiply each grade by unit credit, sum them, divide by total units.' },
  ],
};

export const WELCOME_MSG = "Hello! 👋 I'm your **University Assistant**. I can help you with enrollment, schedules, tuition, campus services, and more. How can I help you today?";

export const SUGGESTED_QUESTIONS = [
  "How do I enroll for the next semester?",
  "What courses or degree programs does UDM offer?",
  "Can I wear civilian clothes instead of the uniform?",
  "What are the admission requirements for freshmen?",
  "Who do I contact for registrar concerns?",
  "How do I compute my General Weighted Average (GWA)?",
];