import type { LoginRequestDto } from "../dto/login-request.dto";
import type { LoginResponseDto } from "../dto/login-response.dto";
import http from "../interceptors/auth.interceptors";

export class AuthService {

    async login(data: LoginRequestDto): Promise<LoginResponseDto> {
        const response = await http.post<LoginResponseDto>('/login', data);

        localStorage.setItem('token', response.data.data.token);

        return response.data;
    }

    logout() {
        localStorage.removeItem('token')
    }

    getToken(): string | null {
        return localStorage.getItem("token");
    }

    isAuthenticated(): boolean {
        return !!this.getToken();
    }
}