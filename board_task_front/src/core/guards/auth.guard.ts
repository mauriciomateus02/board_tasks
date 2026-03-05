import type { NavigationGuard } from "vue-router";

export const authGuard: NavigationGuard = (to) => {
  const token = localStorage.getItem("token");

  if (!token && to.name !== "login") {
    return { name: "login" };
  }

  return true;
};