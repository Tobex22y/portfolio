import { Request, Response } from "express";
import { userService } from "../services/userService";
import { CreateUserInput } from "../types/user";

export const getAllUsers = (req: Request, res: Response): void => {
  const users = userService.getAll();
  res.json({ success: true, count: users.length, data: users });
};

export const getUserById = (req: Request, res: Response): void => {
  const id = Number(req.params.id);
  if (isNaN(id)) {
    res.status(400).json({ success: false, error: "Invalid user ID" });
    return;
  }

  const user = userService.getById(id);
  if (!user) {
    res.status(404).json({ success: false, error: "User not found" });
    return;
  }

  res.json({ success: true, data: user });
};

export const createUser = (req: Request, res: Response): void => {
  const { name, email, role } = req.body;

  if (!name || !email) {
    res.status(400).json({ success: false, error: "Name and email are required" });
    return;
  }

  const input: CreateUserInput = { name, email, role: role || "user" };
  const newUser = userService.create(input);

  res.status(201).json({ success: true, message: "User created", data: newUser });
};
