export interface User {
  id: number;
  name: string;
  email: string;
  role: "admin" | "user";
  createdAt: Date;
}

export type CreateUserInput = Omit<User, "id" | "createdAt">;
