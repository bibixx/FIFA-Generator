interface JsonApiError {
  source?: string;
  title?: string;
}

export class ApiError {
  private source?: string;

  private title?: string;

  public status: number;

  public constructor(status: number, title?: string, source?: string) {
    this.status = status;
    this.title = title;
    this.source = source;

    if (title === undefined) {
      switch (status) {
        case 404: {
          this.title = 'not found';
          break;
        }
        case 403: {
          this.title = 'forbidden';
          break;
        }
        case 422: {
          this.title = 'unprocessable entity';
          break;
        }

        // no default
      }
    }
  }

  public toToJsonApi(): JsonApiError {
    return {
      title: this.title,
      source: this.source,
    };
  }
}

interface ApiErrorResponse {
  errors: JsonApiError[];
}

export const formatErrors = (errors: ApiError[]): ApiErrorResponse => ({
  errors: errors.map((error): JsonApiError => error.toToJsonApi()),
});
