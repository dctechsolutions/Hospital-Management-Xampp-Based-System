import express from "express";
import http from "http";
import path from "path";
import { spawn, exec } from "child_process";
import { createServer as createViteServer } from "vite";

const app = express();
const PORT = 3000;
const PHP_PORT = 8080;

// Ensure MySQL is running
function ensureMySQL() {
  exec("service mysql status", (err, stdout) => {
    if (err || !stdout.includes("running")) {
      console.log("Starting MySQL service...");
      exec("service mysql start", (startErr) => {
        if (startErr) console.error("MySQL start error:", startErr);
        else console.log("MySQL service started successfully.");
      });
    } else {
      console.log("MySQL is active and running.");
    }
  });
}

// Ensure PHP Built-in Server is running
let phpProcess: any = null;
function startPhpServer() {
  ensureMySQL();
  const docRoot = process.cwd();
  console.log(`Starting PHP built-in server on port ${PHP_PORT} pointing to ${docRoot}`);
  
  phpProcess = spawn("php", ["-S", `127.0.0.1:${PHP_PORT}`, "-t", docRoot], {
    stdio: "inherit",
  });

  phpProcess.on("error", (err: any) => {
    console.error("PHP server process error:", err);
  });

  phpProcess.on("exit", (code: number) => {
    console.log(`PHP server exited with code ${code}. Restarting in 2s...`);
    setTimeout(startPhpServer, 2000);
  });
}

// Start PHP
startPhpServer();

// Health check endpoint
app.get("/api/health", (req, res) => {
  res.json({ status: "ok", hospital: "Yasmeen Maternity and Medical Center (R-85647)" });
});

// Proxy helper for PHP server
function proxyToPhp(req: express.Request, res: express.Response) {
  const targetUrl = new URL(req.originalUrl, `http://127.0.0.1:${PHP_PORT}`);
  
  const headers = { 
    ...req.headers, 
    host: `127.0.0.1:${PHP_PORT}`,
    "x-forwarded-host": req.headers.host || "localhost:3000",
    "x-forwarded-proto": req.protocol || "http",
  };

  const proxyReq = http.request(
    {
      hostname: "127.0.0.1",
      port: PHP_PORT,
      path: targetUrl.pathname + targetUrl.search,
      method: req.method,
      headers: headers,
    },
    (proxyRes) => {
      // If redirect location contains 127.0.0.1:8080, rewrite to client host
      if (proxyRes.headers.location) {
        proxyRes.headers.location = proxyRes.headers.location.replace(
          `http://127.0.0.1:${PHP_PORT}`,
          ""
        );
      }
      res.writeHead(proxyRes.statusCode || 200, proxyRes.headers);
      proxyRes.pipe(res, { end: true });
    }
  );

  proxyReq.on("error", (err) => {
    console.error("PHP Proxy Error:", err.message);
    res.status(502).send(`PHP Backend Unavailable: ${err.message}`);
  });

  req.pipe(proxyReq, { end: true });
}

// Route PHP requests
app.use("/hospital-management-system", (req, res) => {
  proxyToPhp(req, res);
});

app.use("/hms", (req, res) => {
  req.originalUrl = req.originalUrl.replace(/^\/hms/, "/hospital-management-system/public");
  proxyToPhp(req, res);
});

async function start() {
  // Vite middleware in dev mode
  if (process.env.NODE_ENV !== "production") {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: "spa",
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), "dist");
    app.use(express.static(distPath));
    app.get("*", (req, res) => {
      res.sendFile(path.join(distPath, "index.html"));
    });
  }

  app.listen(PORT, "0.0.0.0", () => {
    console.log(`Yasmeen HMS server running on http://0.0.0.0:${PORT}`);
  });
}

start();
