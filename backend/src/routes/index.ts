import { Router } from 'express';
import asyncHandler from '../utils/asyncHandler';

const router = Router();

router.get('/fail', asyncHandler(async (): Promise<void> => {
  const a = {};

  // @ts-ignore
  a.b.c = 1;
}));

router.get('/fail2', asyncHandler(async (): Promise<void> => {
  throw new Error('asd');
}));

router.get('/success', asyncHandler(async (_req, res): Promise<void> => {
  res.json({
    yay: true,
  });
}));

export default router;
