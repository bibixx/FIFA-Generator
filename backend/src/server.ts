import express from 'express';
const app = express();
const { PORT } = process.env;

app.get('/health', (_req, res) => {
  res.json({ ok: true });
})

app.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
})
