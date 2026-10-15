import { User, CreateUserInput } from "../types/user";

const users: User[] = [
  { id: 1, name: "Alice Johnson", email: "alice@example.com", role: "admin", createdAt: new Date() },
  { id: 2, name: "Bob Smith", email: "bob@example.com", role: "user", createdAt: new Date() }
];

export const userService = {
  getAll: (): User[] => {
    return users;
  },

  getById: (id: number): User | undefined => {
    return users.find(u => u.id === id);
  },

  create: (input: CreateUserInput): User => {
    const nextId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
    const newUser: User = {
      id: nextId,
      name: input.name,
      email: input.email,
      role: input.role || "user",
      createdAt: new Date()
    };
    users.push(newUser);
    return newUser;
  }
};
