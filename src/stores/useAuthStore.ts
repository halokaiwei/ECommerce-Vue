import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isLoggedIn: !!localStorage.getItem('token'),
    user: JSON.parse(localStorage.getItem('user') || 'null') as null | { username: string; email: string; role: string; image: Blob | null },
  }),
  actions: {
    login(user: { username: string; email: string; role: string; image: Blob | null }) {
      this.isLoggedIn = true;
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user));
      localStorage.setItem('token', 'dummy-token');
    },
    logout() {
      this.isLoggedIn = false;
      this.user = null;
      localStorage.removeItem('user');
      localStorage.removeItem('token');
    }
  },
  getters: {
    isAuthenticated: (state) => state.isLoggedIn,
    getUser: (state) => state.user,
  },
});
