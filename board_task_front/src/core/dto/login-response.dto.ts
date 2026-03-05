import type { User } from "../../types/user";

export interface LoginResponseDto {
  message: string;
  data: {
    user: User;
    token: string;
  };
}