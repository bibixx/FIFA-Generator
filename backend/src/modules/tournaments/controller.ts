import { RequestHandler } from 'express';

import { ApiError, formatErrors } from 'app/utils/jsonapi/responses/errors';
import { formatObject } from 'app/utils/jsonapi/responses/object';

import {
  createTournament as createTournamentService,
  getTournaments as getTournamentsService,
  getTournamentById as getTournamentByIdService,
  deleteTournamentById as deleteTournamentByIdService,
} from 'app/modules/tournaments/service';

interface TournamentsResponse {
  id: string;
}

export const createTournament: RequestHandler = async (_req, res): Promise<void> => {
  const newTournament = await createTournamentService();

  const response: TournamentsResponse = {
    id: newTournament.uuid,
  };

  res.status(201).json(formatObject(response));
};

export const getTournaments: RequestHandler = async (_req, res): Promise<void> => {
  const tournaments = await getTournamentsService();

  const response: TournamentsResponse[] = tournaments
    .map(({ uuid }): TournamentsResponse => ({
      id: uuid,
    }));

  res.json(formatObject(response));
};

export const getTournamentById: RequestHandler = async (req, res): Promise<void> => {
  const uuidSearchedBy = req.params.uuid;
  const tournament = await getTournamentByIdService(uuidSearchedBy);

  if (tournament === null) {
    res
      .status(404)
      .json(
        formatErrors([
          new ApiError('not found'),
        ]),
      );

    return;
  }

  const response: TournamentsResponse = {
    id: tournament.uuid,
  };

  res.json(formatObject(response));
};

export const deleteTournamentById: RequestHandler = async (req, res): Promise<void> => {
  const uuidSearchedBy = req.params.uuid;

  await deleteTournamentByIdService(uuidSearchedBy);

  res.status(204).send();
};
