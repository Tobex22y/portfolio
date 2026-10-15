import express from "express";
import userRoutes from "./routes/userRoutes";

const app = express();
const PORT = 3000;

app.use(express.json());

// Request logger middleware
app.use((req, res, next) => {
  console.log(`[${new Date().toLocaleTimeString()}] ${req.method} ${req.url}`);
  next();
});

app.use("/api/users", userRoutes);

app.get("/", (req, res) => {
  res.json({ message: "TypeScript Backend API is running smoothly!" });
});

export { app, PORT };

if (process.env.NODE_ENV !== "test") {
  app.listen(PORT, () => {
    console.log(`?? Server listening at http://localhost:${PORT}`);
  });
}
