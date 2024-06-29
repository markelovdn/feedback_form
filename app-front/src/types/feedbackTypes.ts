import type { TService } from "./serviceTypes";

export interface StoreFeedbackResponse {
    id: number;
}

export type TFeedbackPayload = {
    title: string;
    description: string;
    datetime: string;
    rating: number;
    serviceId: number;
};

export interface FeedbackResponse {
    id: number;
    title: string;
    description: string;
    datetime: string;
    rating: number;
    service: TService;
}
