export type PlaceStatus = 'pending' | 'approved' | 'rejected';

export type PlaceImage = {
    id: number;
    url: string;
};

export type CategoryInfo = {
    id: number;
    name: string;
    slug: string;
    icon: string | null;
};

export type ReviewCategory = {
    id: string;
    label: string;
    icon: string | null;
    count: number;
};

export type Place = {
    id: number;
    name: string;
    description: string;
    category: CategoryInfo;
    address: string;
    city: string;
    province?: string;
    country?: string;
    latitude?: number | string;
    longitude?: number | string;
    status: PlaceStatus;
    created_at: string;
    images?: PlaceImage[];
};