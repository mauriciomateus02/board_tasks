import type { NotificationSetting } from "@/types/notification-setting";
import http from "../interceptors/auth.interceptors";

export class NotificationService {

    async getSetting() {
        const response = await http.get<NotificationSetting>('/notification-settings');
        return response.data;
    }

    async saveSetting(data: { days_before: number }) {
        const response = await http.post<NotificationSetting>('/notification-settings', data);
        return response.data;
    }
}