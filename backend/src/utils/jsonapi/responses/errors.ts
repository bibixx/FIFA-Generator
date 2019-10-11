interface JsonApiError {
  source?: string;
  title: string;
}

export class ApiError {
  private source?: string;

  private title: string;

  public constructor(title: string, source?: string) {
    this.title = title;
    this.source = source;
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
